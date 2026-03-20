<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CrudGenerateCommand extends Command
{
    protected $signature = 'helpers:crud
        {model : Nombre del modelo en StudlyCase, por ejemplo Product}
        {--domain= : Nombre del dominio, por defecto el plural del modelo}
        {--index : Genera la accion index}
        {--show : Genera la accion show}
        {--store : Genera la accion store}
        {--update : Genera la accion update}
        {--destroy : Genera la accion destroy}';

    protected $description = 'Genera CRUD con controllers single action, actions, requests y rutas';

    public function __construct(private readonly Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $model = Str::studly((string) $this->argument('model'));
        $domain = $this->resolveDomain($model);
        $actions = $this->resolveActions();

        $basePath = app_path("Domains/{$domain}");
        $controllersPath = "{$basePath}/Controllers";
        $actionsPath = "{$basePath}/Actions";
        $requestsPath = "{$basePath}/Requests";
        $routesPath = "{$basePath}/Routes/api.php";

        $this->ensureDirectory($controllersPath);
        $this->ensureDirectory($actionsPath);
        $this->ensureDirectory($requestsPath);
        $this->ensureDirectory(dirname($routesPath));

        foreach ($actions as $action) {
            $controllerClass = Str::studly($action)."{$model}Controller";
            $actionClass = Str::studly($action)."{$model}Action";
            $controllerFile = "{$controllersPath}/{$controllerClass}.php";
            $actionFile = "{$actionsPath}/{$actionClass}.php";

            if (! $this->files->exists($controllerFile)) {
                $this->files->put(
                    $controllerFile,
                    $this->buildController($domain, $model, $action, $controllerClass, $actionClass)
                );
                $this->info("Creado: {$controllerFile}");
            } else {
                $this->warn("Ya existe: {$controllerFile}");
            }

            if (! $this->files->exists($actionFile)) {
                $this->files->put(
                    $actionFile,
                    $this->buildAction($domain, $model, $action, $actionClass)
                );
                $this->info("Creado: {$actionFile}");
            }

            if (in_array($action, ['store', 'update'], true)) {
                $requestClass = Str::studly($action)."{$model}Request";
                $requestFile = "{$requestsPath}/{$requestClass}.php";

                if (! $this->files->exists($requestFile)) {
                    $this->files->put(
                        $requestFile,
                        $this->buildRequest($domain, $requestClass)
                    );
                    $this->info("Creado: {$requestFile}");
                }
            }

            $this->appendRoute($routesPath, $domain, $model, $action);
        }

        $this->newLine();
        $this->info('CRUD generado correctamente.');

        return self::SUCCESS;
    }

    private function resolveDomain(string $model): string
    {
        $domainOption = $this->option('domain');

        if (is_string($domainOption) && $domainOption !== '') {
            return Str::studly($domainOption);
        }

        return Str::pluralStudly($model);
    }

    /**
     * @return array<int, string>
     */
    private function resolveActions(): array
    {
        $availableActions = ['index', 'show', 'store', 'update', 'destroy'];
        $selectedActions = [];

        foreach ($availableActions as $action) {
            if ((bool) $this->option($action)) {
                $selectedActions[] = $action;
            }
        }

        return $selectedActions === [] ? $availableActions : $selectedActions;
    }

    private function ensureDirectory(string $path): void
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }

    private function appendRoute(string $routesPath, string $domain, string $model, string $action): void
    {
        $controllerClass = Str::studly($action)."{$model}Controller";
        $uri = Str::kebab(Str::pluralStudly($model));
        $parameter = Str::camel($model);
        $controllerImport = "use App\\Domains\\{$domain}\\Controllers\\{$controllerClass};";

        $routeLine = match ($action) {
            'index' => "Route::get('{$uri}', {$controllerClass}::class);",
            'show' => "Route::get('{$uri}/{{$parameter}}', {$controllerClass}::class);",
            'store' => "Route::post('{$uri}', {$controllerClass}::class);",
            'update' => "Route::match(['put', 'patch'], '{$uri}/{{$parameter}}', {$controllerClass}::class);",
            'destroy' => "Route::delete('{$uri}/{{$parameter}}', {$controllerClass}::class);",
            default => '',
        };

        if ($routeLine === '') {
            return;
        }

        if (! $this->files->exists($routesPath)) {
            $this->files->put(
                $routesPath,
                "<?php\n\n{$controllerImport}\nuse Illuminate\\Support\\Facades\\Route;\n\n{$routeLine}\n"
            );
            $this->info("Actualizado: {$routesPath}");

            return;
        }

        $content = $this->files->get($routesPath);

        if (! Str::contains($content, 'use Illuminate\\Support\\Facades\\Route;')) {
            $content = preg_replace(
                '/^<\?php\s*/',
                "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n",
                $content
            ) ?? $content;
        }

        if (! Str::contains($content, $controllerImport)) {
            $content = str_replace(
                'use Illuminate\\Support\\Facades\\Route;',
                "{$controllerImport}\nuse Illuminate\\Support\\Facades\\Route;",
                $content
            );
        }

        if (! Str::contains($content, $routeLine)) {
            $content = rtrim($content)."\n{$routeLine}\n";
            $this->files->put($routesPath, $content);
            $this->info("Actualizado: {$routesPath}");
        }
    }

    private function buildController(
        string $domain,
        string $model,
        string $action,
        string $controllerClass,
        string $actionClass
    ): string {
        $actionVariable = Str::camel($actionClass);
        $requestImport = '';
        $requestArgument = 'Request $request';
        $executeArguments = '$request->all()';

        if (in_array($action, ['store', 'update'], true)) {
            $requestClass = Str::studly($action)."{$model}Request";
            $requestImport = "use App\\Domains\\{$domain}\\Requests\\{$requestClass};\n";
            $requestArgument = "{$requestClass} \$request";
            $executeArguments = '$request->validated()';
        }

        if (in_array($action, ['show', 'update', 'destroy'], true)) {
            $parameterVariable = Str::camel($model);
            $requestArgument .= ", mixed \${$parameterVariable}";
            $executeArguments .= ", \${$parameterVariable}";
        }

        $baseRequestImport = in_array($action, ['store', 'update'], true)
            ? ''
            : "use Illuminate\\Http\\Request;\n";
        $actionImport = "use App\\Domains\\{$domain}\\Actions\\{$actionClass};\n";

        return <<<PHP
<?php

namespace App\\Domains\\{$domain}\\Controllers;

use App\\Http\\Controllers\\Controller;
{$baseRequestImport}{$actionImport}{$requestImport}
class {$controllerClass} extends Controller
{
    public function __construct(private readonly {$actionClass} \${$actionVariable})
    {
    }

    public function __invoke({$requestArgument})
    {
        return \$this->{$actionVariable}->execute({$executeArguments});
    }
}
PHP;
    }

    private function buildAction(string $domain, string $model, string $action, string $actionClass): string
    {
        $parameter = Str::camel($model);

        $executeSignature = match ($action) {
            'show', 'update', 'destroy' => "public function execute(array \$data, mixed \${$parameter}): mixed",
            default => 'public function execute(array $data): mixed',
        };

        $body = match ($action) {
            'index' => "        return [\n            'message' => 'Implement index action',\n            'filters' => \$data,\n        ];",
            'show' => "        return [\n            'message' => 'Implement show action',\n            'id' => \${$parameter},\n        ];",
            'store' => "        return [\n            'message' => 'Implement store action',\n            'payload' => \$data,\n        ];",
            'update' => "        return [\n            'message' => 'Implement update action',\n            'id' => \${$parameter},\n            'payload' => \$data,\n        ];",
            'destroy' => "        return [\n            'message' => 'Implement destroy action',\n            'id' => \${$parameter},\n        ];",
            default => "        return ['message' => 'Not implemented'];",
        };

        return <<<PHP
<?php

namespace App\\Domains\\{$domain}\\Actions;

class {$actionClass}
{
    {$executeSignature}
    {
{$body}
    }
}
PHP;
    }

    private function buildRequest(string $domain, string $requestClass): string
    {
        return <<<PHP
<?php

namespace App\\Domains\\{$domain}\\Requests;

use Illuminate\\Foundation\\Http\\FormRequest;

class {$requestClass} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [];
    }
}
PHP;
    }
}
