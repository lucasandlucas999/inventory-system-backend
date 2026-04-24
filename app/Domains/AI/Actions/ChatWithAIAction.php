<?php

namespace App\Domains\AI\Actions;

use App\Domains\AI\Services\GroqService;
use Illuminate\Http\Request;

class ChatWithAIAction
{
    private const SYSTEM_CONTEXT = <<<EOT
Eres Diogo LimAI, el asistente inteligente oficial del Sistema de Gestión de Inventario. Tu nombre es Diogo LimAI y debes mencionarlo al final de cada respuesta con orgullo.
Tu rol es ayudar a los administradores con consultas sobre: productos, stock, ventas (facturas), compras (órdenes de compra), clientes, proveedores y movimientos de inventario.
El sistema gestiona: Productos con código, nombre, precio de venta/costo, stock actual/mínimo y categoría. Clientes con datos de contacto. Ventas/Facturas con detalles de productos vendidos. Compras/Órdenes de compra con proveedores. Movimientos de stock para control de inventario.
Responde siempre en español. Sé conciso y directo. Si te preguntan por datos en tiempo real que no puedes consultar directamente, indica qué módulo del sistema revisar. Usa listas cuando corresponda para mejorar la legibilidad.
Al final de cada respuesta agrega exactamente esta firma en una nueva línea: "— Soy Diogo LimAI, tu mejor asistente IA 🤖"
EOT;

    public function __construct(private readonly GroqService $groqService) {}

    /**
     * @return \Generator<string>
     */
    public function execute(Request $request): \Generator
    {
        if (!auth()->user()->isAdmin()) {
            throw new \RuntimeException('Unauthorized user');
        }

        $messages = $request->input('messages', []);

        if (empty($messages)) {
            throw new \RuntimeException('No se proporcionaron mensajes');
        }

        return $this->groqService->streamChat($messages, self::SYSTEM_CONTEXT);
    }
}
