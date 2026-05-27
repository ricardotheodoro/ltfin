// Draggable - a lightweight, responsive, modern drag & drop library: https://shopify.github.io/draggable/
// API mudou na v1.0+: o pacote agora expõe tudo a partir do entry principal.
// Mantemos disponível em window.* para compatibilidade com o tema.

const Draggable = require('@shopify/draggable');

window.Draggable = Draggable.Draggable;
window.Sortable = Draggable.Sortable;
window.Droppable = Draggable.Droppable;
window.Swappable = Draggable.Swappable;
window.Plugins = Draggable.Plugins;
