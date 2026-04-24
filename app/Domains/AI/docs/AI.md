# Dominio AI — Backend

Streaming de chat con IA usando Server-Sent Events (SSE).

---

## Estructura

```
Domains/AI/
├── Actions/ChatWithAIAction.php   — valida admin, arma contexto, invoca el servicio
├── Controllers/ChatAIController.php — retorna StreamedResponse (text/event-stream)
├── Services/GroqService.php       — llama a Groq API con streaming real
└── Routes/api.php                 — POST /api/ai/chat (auth:api)
```

---

## Configuración

### 1. Variables de entorno (`.env`)

```env
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxx
```

### 2. Obtener API Key

1. Registrarse en [console.groq.com](https://console.groq.com) (gratis)
2. **API Keys → Create API Key**
3. Copiar y pegar en `.env`

### 3. Limpiar caché tras cambiar `.env`

```bash
php artisan config:clear
```

---

## Modelo en uso

`llama-3.3-70b-versatile` — configurable en `GroqService::$model`.

Otros modelos disponibles: `llama-3.1-8b-instant`, `gemma2-9b-it`.

---

## Endpoint

```
POST /api/ai/chat
Authorization: Bearer {jwt_token}
Content-Type: application/json

{
  "messages": [
    { "role": "user", "content": "¿Cuántos productos hay en stock bajo mínimo?" }
  ]
}
```

**Respuesta:** `text/event-stream`

```
data: {"text":"Puedes verificarlo en el módulo "}
data: {"text":"Inventario → Stock Movements."}
data: [DONE]
```

---

## Cambiar proveedor de IA

Reemplazar `GroqService` por cualquier servicio que implemente el mismo contrato:

```php
public function streamChat(array $messages, string $systemContext): \Generator
```

Actualizar el constructor de `ChatWithAIAction` para inyectar el nuevo servicio.
