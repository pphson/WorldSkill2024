const sampleImageUrls = [
   'media/img/sample_photos/basilique-notre-dame-de-fourviere-lyon.jpg',
   'media/img/sample_photos/beautiful-view-in-lyon.jpg',
   'media/img/sample_photos/place-bellecour-lyon.jpg',
   'media/img/sample_photos/tour-metalique-lyon.jpg',
];
document.addEventListener("DOMContentLoaded", function () {
   const dropZone = document.getElementById("drop-zone");
   const fileInput = document.getElementById("file-input");
   const preview = document.getElementById("preview-list");
   const btnClear = document.getElementById("btn-clear");
   const btnLoadSample = document.getElementById("btn-load-sample-images");
   const btnSlideshow = document.getElementById("btn-start-slideshow");
   const rdbThemeSelection = document.getElementById("rdb-theme-selection");
   const rdbSlideshowMode = document.getElementById("rdb-slideshow-mode");
   const slideshowContainer = document.getElementById("slideshow-container");
   const commandbar = document.getElementById("command-bar");
   const dlgConfirm = document.getElementById("clear-confirm");

   window.addEventListener("drop", (e) => {
      if ([...e.dataTransfer.items].some((item) => item.kind === "file")) {
         e.preventDefault();
      }
   });

   dropZone.addEventListener("dragover", (e) => {
      const fileItems = [...e.dataTransfer.items].filter(
         (item) => item.kind === "file",
      );
      if (fileItems.length > 0) {
         e.preventDefault();
         if (fileItems.some((item) => item.type.startsWith("image/"))) {
            e.dataTransfer.dropEffect = "copy";
         } else {
            e.dataTransfer.dropEffect = "none";
         }
      }
   });

   window.addEventListener("dragover", (e) => {
      const fileItems = [...e.dataTransfer.items].filter(
         (item) => item.kind === "file",
      );
      if (fileItems.length > 0) {
         e.preventDefault();
         if (!dropZone.contains(e.target)) {
            e.dataTransfer.dropEffect = "none";
         }
      }
   });

   function generatePreview(src, alt) {
      const img = document.createElement("img");
      const caption = document.createElement("span");
      alt = alt || "No caption";
      alt = alt.replace(/\.[^.]+?$/, "");
      let words = alt.match(/[a-zA-Z0-9]+/g) || [];
      words = words.map(word => {
         let firstChar = word.charAt(0).toUpperCase();
         let restChars = word.slice(1).toLowerCase();
         return firstChar + restChars;
      });
      alt = words.join(" ");
      img.src = src;
      img.alt = alt;
      caption.textContent = alt;
      const li = document.createElement("li");
      li.title = alt;
      li.appendChild(img);
      li.appendChild(caption);
      li.draggable = true;
      preview.appendChild(li);
   }

   function displayImages(files) {
      if (!files) {
         for (const url of sampleImageUrls) {
            generatePreview(url, url.split("/").pop());
         }
         return;
      }
      for (const file of files) {
         if (file.type.startsWith("image/")) {
            generatePreview(URL.createObjectURL(file), file.name);
         }
      }
   }

   function checkImageCount() {
      if (preview.querySelectorAll("li").length ==0){
         btnClear.disabled = true;
         btnClear.title = "No photos to clear.";
      }
      else {
         btnClear.disabled = false;
         btnClear.title = "Clear all photos.";
      }
      if (preview.querySelectorAll("li").length <= 3) {
         rdbSlideshowMode.querySelectorAll("input").forEach(input => input.disabled = true);
         rdbThemeSelection.querySelectorAll("input").forEach(input => input.disabled = true);
         commandbar.classList.add("disabled");
         btnSlideshow.disabled = true;
         btnSlideshow.title = "Please add at least 4 photos to start the slideshow.";
      }
      else {
         rdbSlideshowMode.querySelectorAll("input").forEach(input => input.disabled = false);
         rdbThemeSelection.querySelectorAll("input").forEach(input => input.disabled = false);
         commandbar.classList.remove("disabled");
         btnSlideshow.disabled = false;
         btnSlideshow.title = "Start the slideshow";
      }
   }

   function dropHandler(ev) {
      ev.preventDefault();
      const files = [...ev.dataTransfer.items]
         .map((item) => item.getAsFile())
         .filter((file) => file);
      displayImages(files);
      checkImageCount();
   }


   dropZone.addEventListener("drop", dropHandler);

   fileInput.addEventListener("change", (e) => {
      displayImages(e.target.files);
      checkImageCount();
   });
   dlgConfirm.onClose = () => {
      if (!dlgConfirm.ok) { return; }
      for (const img of preview.querySelectorAll("img")) {
         if (img.src.startsWith("blob:")) {
            URL.revokeObjectURL(img.src);
         }
      }
      preview.textContent = "";
      slideshowContainer.innerHTML = "";
      checkImageCount();
   }
   btnClear.addEventListener("click", () => {
      dlgConfirm.show();
   });

   btnLoadSample.addEventListener("click", () => {
      displayImages(null);
      checkImageCount();
   });

   checkImageCount();

   //debug: load sample images on page load
   // document.getElementById("btn-load-sample-images").click();

});