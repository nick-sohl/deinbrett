# Architecture

## Backend: Clean Architecture

The backend follows Clean Architecture with entities, use cases, repositories, and controllers living in `src/`.

```
src/
  Entity/       ← Domain models, business rules, validation
  Repository/   ← Data access (SQLite)
  Service/      ← Use cases, application logic
  Controller/   ← HTTP handlers
```

## Frontend: Feature-Sliced Design (adapted for HTMX)

The frontend uses Feature-Sliced Design principles, but adapted for a server-rendered PHP + HTMX stack instead of a JS SPA.

```
views/
  pages/           ← Full page templates
    board/
      show.php
      partials/    ← HTML fragments returned by HTMX requests
  entities/        ← Reusable UI for domain concepts
    board/
      card.php
    user/
      avatar.php
  features/        ← Self-contained interactive features
    board-reorder/
      reorder.php
      reorder.js
  shared/          ← Reusable components and utilities
    components/
      modal.php
      button.php
    js/
      confirm-dialog.js
```

## Backend Entities vs Frontend Entities

Both layers use the same domain language (User, Board) but serve different purposes.

| | Backend Entity | Frontend Entity |
|---|---|---|
| Concern | Business rules, data integrity | Presentation, markup |
| Knows about | Validation, persistence | Components, layout |
| Example | "Board title must be 3-100 chars" | "Render board title in a card" |
| Lives in | `src/Entity/` | `views/entities/` |

They are not redundant. The backend enforces truth, the frontend displays it.

## HTMX and the Data Flow

### Traditional SPA

```
Browser JS  →  fetch("/api/boards")  →  Server returns JSON  →  JS renders HTML
```

### HTMX (this project)

```
Browser HTML  →  hx-get="/boards"  →  Server returns HTML fragment  →  HTMX swaps it in
```

PHP routes are the API — they return HTML instead of JSON. There is no need for a frontend `api/` layer.

## Three Tiers of Interactivity

1. **PHP** — does the heavy lifting: renders pages, returns HTML fragments, handles all data
2. **HTMX** — handles interactivity declaratively via HTML attributes
3. **JavaScript** — fills rare gaps only when the browser needs something HTMX cannot express

### When JS Is Needed

| Use case | Why |
|---|---|
| Drag-and-drop | Complex pointer tracking |
| Rich text editing | Dedicated editor libraries |
| Instant client-side validation | Before the request leaves the browser |
| Complex animations | Beyond CSS transitions |

### JS + HTMX Integration

JavaScript handles the browser interaction, then delegates persistence back to HTMX:

```js
// reorder.js
sortable.on('end', (evt) => {
  htmx.ajax('PUT', '/boards/1/reorder', {
    values: { order: getNewOrder() }
  });
});
```

```html
<script src="/views/features/board-reorder/reorder.js"></script>
```
