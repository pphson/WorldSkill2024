
document.addEventListener("DOMContentLoaded", function () {

   const slideOptions = {
      duration: 2000,
   };
   const slideShow = document.getElementById("slideshow");
   const slideshowContainer = document.getElementById("slideshow-container");
   const btnSlideshow = document.getElementById("btn-start-slideshow");
   const btnFullscreen = document.getElementById("btn-fullscreen");
   const btnExitFullscreen = document.getElementById("btn-exit-fullscreen");
   const rdbThemeSelection = document.getElementById("rdb-theme-selection");
   const rdbSlideshowMode = document.getElementById("rdb-slideshow-mode");
   const commandBar = document.getElementById("command-bar");
   const btnExecuteCommand = document.getElementById("btn-execute-command");
   const txtCommand = document.getElementById("txtCommand");
   const frmCommand = commandBar.querySelector("form");

   let slideCount = 0;
   let currentIndex = 0;
   let nextIndex = 0;
   let previousIndex = 0;
   let shownCount = 0;

   function getNextSlideIndex() {
      if (slideOptions.mode === "random") {
         const slides = [...slideshowContainer.querySelectorAll(".slide")];
         const candidates = [];
         if (shownCount >= slideCount) {
            candidates.push(...slideshowContainer.querySelectorAll(".slide:not(.active, .previous, .next)"));
         }
         else {
            candidates.push(...slideshowContainer.querySelectorAll(".slide:not(.shown, .active, .previous, .next)"));
         }
         // console.log("Candidate slides for random selection:", candidates.map(slide => slide.querySelector(".caption").textContent));
         const randomSlide = candidates[Math.floor(Math.random() * candidates.length)];
         return slides.indexOf(randomSlide);
      }
      return (currentIndex + 1) % slideCount;
   }
   function nextSlide() {
      const oldNextIndex = nextIndex;
      const slides = [...slideshowContainer.querySelectorAll(".slide")];
      if (slides.length < 3) {
         if (slideOptions.intervalId) {
            clearInterval(slideOptions.intervalId);
            slideOptions.intervalId = null;
         }
         return;
      }
      slides[previousIndex].classList.remove("previous");
      slides[currentIndex].classList.remove("active");
      slides[nextIndex].classList.remove("next");
      if (shownCount >= slideCount) {
         // reset
         shownCount = 0;
         slides.forEach(slide => {
            slide.classList.remove("shown");
            slide.style.removeProperty("--order");
         });
      }
      previousIndex = currentIndex;
      slides[previousIndex].classList.add("previous");
      currentIndex = nextIndex;
      slides[currentIndex].classList.add("active");
      slides[currentIndex].classList.add("shown");
      slides[currentIndex].style.setProperty("--order", shownCount);
      slides[previousIndex].classList.add("previous");
      shownCount++;
      nextIndex = getNextSlideIndex();
      slides[nextIndex].classList.add("next");
      console.log(`Shown: ${shownCount}, Current: ${currentIndex}, Next: ${nextIndex}, Previous: ${previousIndex}`);

   };
   function previousSlide() {
      // No random mode for previous slide, just go back in order
      const slides = [...slideshowContainer.querySelectorAll(".slide")];
      slides[previousIndex].classList.remove("previous");
      slides[currentIndex].classList.remove("active");
      slides[nextIndex].classList.remove("next");
      nextIndex = currentIndex;
      slides[nextIndex].classList.add("next");
      slides[nextIndex].classList.remove("shown");
      slides[nextIndex].style.removeProperty("--order");
      currentIndex = previousIndex;
      slides[currentIndex].classList.add("active");
      previousIndex = (currentIndex - 1 + slideCount) % slideCount;
      slides[previousIndex].classList.add("previous");
      if (shownCount > 1) {
         shownCount--;
      }
      else {
         shownCount = slideCount;
         slides.forEach((slide, index) => {
            slide.classList.add("shown");
            slide.style.setProperty("--order", index + 1);
         });
      }
      console.log(`Shown: ${shownCount}, Current: ${currentIndex}, Next: ${nextIndex}, Previous: ${previousIndex}`);
   };
   function startSlideshow(softReset = false) {
      console.log("Starting slideshow with options:", slideOptions);
      if (!softReset) {
         slideshowContainer.innerHTML = "";
         const images = [...document.getElementById("preview-list").children];
         if (images.length <= 3) {
            alert("Please add at least 4 photos to the slideshow.");
            return;
         }
         slideCount = images.length;
         // theme D
         const rand = [];
         for (let i = 0; i < slideCount; i++) {
            let r;
            do {
               r = Math.floor(Math.random() * 101) / 10 - 5;
            } while (rand.includes(r));
            rand.push(r);
         }
         images.forEach((image, index) => {
            const slide = document.createElement("div");
            slide.classList.add("slide");
            const wrapper = document.createElement("div");
            wrapper.classList.add("wrapper");
            const img = image.querySelector("img").cloneNode();
            const src = img.getAttribute("src");
            const caption = image.querySelector("span").textContent;
            const captionElem = document.createElement("div");
            captionElem.classList.add("caption");
            // theme C
            caption.split(" ").forEach((word, i) => {
               const wordElem = document.createElement("span");
               wordElem.textContent = word;
               wordElem.style.setProperty("--i", i + 1);
               captionElem.appendChild(wordElem);
            });
            wrapper.appendChild(img);
            if (src.startsWith('blob:') || src.startsWith('data:') || src.startsWith('http://') || src.startsWith('https://')) {
               wrapper.style.setProperty("--url", `url('${src}')`);
            }
            else {
               wrapper.style.setProperty("--url", `url('../${img.getAttribute("src")}')`);
            }
            slide.appendChild(wrapper);
            slide.appendChild(captionElem);
            slide.style.setProperty("--r", rand[index]);
            slideshowContainer.appendChild(slide);
         });
      }
      else {
         if (slideCount === 0) { return; }
         slideshowContainer.querySelectorAll(".slide").forEach(slide => {
            slide.classList.remove("shown");
            slide.classList.remove("next");
            slide.classList.remove("active");
            slide.classList.remove("previous");
            slide.style.removeProperty("--order");
         });
      }
      const slides = [...slideshowContainer.querySelectorAll(".slide")];
      currentIndex = 0;
      shownCount = 1;
      previousIndex = slideCount - 1;
      nextIndex = getNextSlideIndex();
      slides[previousIndex].classList.add("previous");
      slides[currentIndex].classList.add("active");
      slides[currentIndex].classList.add("shown");
      slides[currentIndex].style.setProperty("--order", 1);
      slides[nextIndex].classList.add("next");
   };
   function changeMode(newMode) {
      const slides = [...slideshowContainer.querySelectorAll(".slide")];
      const oldMode = slideOptions.mode;
      if (oldMode === newMode) { return }
      slideOptions.mode = newMode;
      const rdbMode = rdbSlideshowMode.querySelector(`input[value="${newMode}"]`);
      if (rdbMode && !rdbMode.checked) {
         rdbMode.checked = true;
      }
      console.log("Selected slideshow mode:", slideOptions.mode);
      // If switching to manual mode, stop the automatic slideshow and enable keyboard navigation
      if (slideOptions.mode === "manual") {
         console.log("Manual mode selected. Slideshow paused.");
         if (slideOptions.intervalId) {
            clearInterval(slideOptions.intervalId);
            slideOptions.intervalId = null;
         }
         slideshowContainer.focus();
         document.addEventListener("keydown", manualSlideChange);
      }
      else {
         document.removeEventListener("keydown", manualSlideChange);
         if (!slideOptions.intervalId) {
            slideOptions.intervalId = setInterval(nextSlide, slideOptions.duration);
         }
      }
      // If switching to or from random mode, reset the slideshow state to ensure proper slide tracking  
      if (slideOptions.mode === "random" || oldMode === "random") {
         startSlideshow(true); // soft reset to reinitialize slide tracking without rebuilding slides
      }
   }
   function changeTheme(newTheme) {
      const oldTheme = slideOptions.theme;
      if (oldTheme === newTheme) { return }
      slideOptions.theme = newTheme;
      const rdbTheme = rdbThemeSelection.querySelector(`input[value="${newTheme}"]`);
      if (rdbTheme && !rdbTheme.checked) {
         rdbTheme.checked = true;
      }
      console.log("Selected theme:", slideOptions.theme);
      const themeLink = document.getElementById(`theme-${slideOptions.theme}`);
      if (themeLink) {
         document.querySelectorAll('link[rel="stylesheet"][id^="theme-"]').forEach(link => {
            link.rel = "alternate";
         });
         themeLink.rel = "stylesheet";
      }
   }
   function manualSlideChange(e) {
      if (e.key === "ArrowRight") {
         nextSlide();
      } else if (e.key === "ArrowLeft") {
         previousSlide();
      }
   }

   rdbSlideshowMode.addEventListener("change", (e) => {
      if (e.target.name !== "slideshow-mode") { return }
      changeMode(e.target.value);
   });
   rdbThemeSelection.addEventListener("change", (e) => {
      if (e.target.name === "theme") {
         changeTheme(e.target.value);
      }
   });

   btnSlideshow.addEventListener("click", () => {
      startSlideshow();
   });
   btnFullscreen.addEventListener("click", () => {
      if (document.fullscreenElement) {
         document.exitFullscreen();
      }

      slideshow.appendChild(commandBar); // move command bar inside slideshow for fullscreen mode
      slideshow.requestFullscreen();

   });
   btnExitFullscreen.addEventListener("click", () => {
      if (document.fullscreenElement === slideshow) {
         document.exitFullscreen();
         slideshow.removeChild(commandBar); // move command bar back outside slideshow when exiting fullscreen mode
      }
   });



   commandBar.onShow = () => {
      txtCommand.reset();
      txtCommand.focus();
   }
   window.addEventListener("keydown", (e) => {
      if (((e.code === "KeyK" && (e.ctrlKey || e.metaKey)) || e.code === "Slash") && !commandBar.classList.contains("active")) {
         e.preventDefault();
         commandBar.show();
      }
   });
   txtCommand.addEventListener("input", () => {
      if (txtCommand.value.trim() === "") {
         btnExecuteCommand.disabled = true;
      }
      else {
         btnExecuteCommand.disabled = false;
      }
   });
   txtCommand.addEventListener("dblclick", () => {
      btnExecuteCommand.click();
   });
   frmCommand.addEventListener("submit", (e) => {
      e.preventDefault();
      const command = txtCommand.value.trim();
      console.log("Entered command:", command);
      if (command) {
         console.log("Executing command:", command);
         const [action, value] = command.split(" ");
         if (action === "theme") {
            changeTheme(value);
         }
         else if (action === "mode") {
            changeMode(value);
         }
      }
      txtCommand.reset();
      commandBar.close();
   });

   changeMode("manual");
   changeTheme("A");
});