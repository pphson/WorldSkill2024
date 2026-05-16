document.addEventListener("DOMContentLoaded", function () {
   const dragList = [...document.querySelectorAll(".drag-list")];
   dragList.forEach((list) => {
      list.draggedItem = null;

      list.addEventListener("dragstart", (e) => {
         if (e.target.tagName !== "LI") return;
         list.draggedItem = e.target;
         list.draggedItem.classList.add("dragging");
         e.dataTransfer.effectAllowed = "move";
      });
      list.addEventListener("dragenter", (e) => {
         if (e.target.tagName === "LI" && e.target !== list.draggedItem) {
            e.target.classList.add("drag-over");
         }
      });
      list.addEventListener("dragleave", (e) => {
         if (e.target.tagName === "LI") {
            e.target.classList.remove("drag-over");
         }
      });
      list.addEventListener("dragover", (e) => {
         if (e.target.tagName === "LI") {
            e.preventDefault();
            e.dataTransfer.dropEffect = "move";
         }
      });
      list.addEventListener("drop", (e) => {
         if (e.target.tagName === "LI" && e.target !== list.draggedItem) {
            e.preventDefault();
            const target = e.target;
            const tmp = document.createElement("li");
            list.draggedItem.replaceWith(tmp);
            target.replaceWith(list.draggedItem);
            tmp.replaceWith(target);
            tmp.remove();
         }
      });
      list.addEventListener("dragend", () => {
         list.draggedItem.classList.remove("dragging");
         [...list.children].forEach((item) => item.classList.remove("drag-over"));
         list.draggedItem = null;
      });
   });
});