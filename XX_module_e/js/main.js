document.addEventListener("DOMContentLoaded", function () {
   function show(modal) {
      if (!modal.classList.contains("disabled")) {
         modal.classList.add("active");
         modal.cancel = false;
         modal.ok = false;
         modal.focus();
         if (modal.onShow) {
            modal.onShow();
         }
      }
   }
   function close(modal) {
      modal.classList.remove("active");
      if (modal.onClose) {
         modal.onClose();
      }
   }
   document.querySelectorAll(".modal").forEach((modal) => {
      modal.show = () => show(modal);
      modal.close = () => close(modal);
      modal.tabIndex = -1;
      modal.addEventListener("click", (e) => {
         if (e.target.classList.contains("modal-close") || e.target.id === modal.dataset.cancelButton || e.target === modal) {
            
            modal.cancel = true;
            modal.close();
         }
         else if (e.target.id === modal.dataset.okButton) {
            
            modal.ok = true;  
            modal.close();          
         }
      });
      modal.addEventListener("keydown", (e) => {
         e.preventDefault();
         e.stopPropagation();         
         if (e.key === "Escape") {
            const btnCancelId = modal.dataset.cancelButton;
            if (btnCancelId) {
               const btnCancel = document.getElementById(btnCancelId);
               if (btnCancel) {
                  btnCancel.click();
               }
            }
         }
         else if (e.key === "Enter") {
            const btnOkId = modal.dataset.okButton;
            if (btnOkId) {
               const btnOk = document.getElementById(btnOkId);
               if (btnOk) {
                  btnOk.click();
               }
            }
         }
      });
   });
   function resetSelectGroup(selectGroup) {
      const input = selectGroup.querySelector("input");
      const select = selectGroup.querySelector("select");
      input.value = "";
      Array.from(select.options).forEach((option) => {
         if (option.value) {
            option.style.display = "block";
         }
         else {
            option.style.display = "none";
         }
      });
      select.selectedIndex = 0;
   }
   function focusSelectGroup(selectGroup) {
      const input = selectGroup.querySelector("input");
      input.focus();
   }
   document.querySelectorAll(".filter-select-group").forEach((group) => {
      group.reset = () => resetSelectGroup(group);
      group.focus = () => focusSelectGroup(group);
      const input = group.querySelector("input");
      const select = group.querySelector("select");
      const options = Array.from(select.options);
      const optionNotFound = document.createElement("option");
      options.forEach((option, index) => {
         option.textContent = option.value;
      });
      optionNotFound.value = "";
      optionNotFound.textContent = "No options found.";
      optionNotFound.disabled = true;
      optionNotFound.style.display = "none";
      select.size = select.options.length;
      select.appendChild(optionNotFound);
      select.selectedIndex = 0;
      select.tabIndex = -1;

      input.addEventListener("input", (e) => {
         const filterValue = input.value.toLowerCase();
         const options = Array.from(select.options);
         const filtered = options.filter((option) => option.value.toLowerCase().includes(filterValue));

         options.forEach((option) => {
            if (filtered.includes(option)) {
               option.style.display = "block";
            } else {
               option.style.display = "none";
            }
         });
         if (filtered.length === 0) {
            optionNotFound.style.display = "block";
            select.selectedIndex = -1;
            group.value = "";
            input.setCustomValidity("No options match your search.");
         } else {
            optionNotFound.style.display = "none";
            if (select.selectedIndex === -1 || !filtered.includes(options[select.selectedIndex])) {
               select.selectedIndex = options.indexOf(filtered[0]);
               group.value = filtered[0].value;
            }
            input.setCustomValidity("");
         }
      });
      input.addEventListener("keydown", (e) => {
         const visibleOptions = options.filter((option) => option.style.display !== "none");
         const currentIndex = visibleOptions.indexOf(select.options[select.selectedIndex]);
         if (e.key === "ArrowDown") {
            e.preventDefault();
            select.selectedIndex = options.indexOf(visibleOptions[(currentIndex + 1) % visibleOptions.length]);
            group.value = select.options[select.selectedIndex].value;
         } else if (e.key === "ArrowUp") {
            e.preventDefault();
            if (currentIndex >= 0) {
               select.selectedIndex = options.indexOf(visibleOptions[(currentIndex - 1 + visibleOptions.length) % visibleOptions.length]);
               group.value = select.options[select.selectedIndex].value;
            }
         }
         else if (e.key === "Enter") {
            e.preventDefault();
            if (select.selectedIndex >= 0) {
               input.value = select.options[select.selectedIndex].value;
               group.value = select.options[select.selectedIndex].value;
            } else {
               input.reportValidity();
               e.stopPropagation();
            }
         }
      });
      select.addEventListener("input", (e) => {
         e.stopPropagation();
         if (select.selectedIndex >= 0) {
            input.value = select.options[select.selectedIndex].value;
            group.value = select.options[select.selectedIndex].value;
         }
      });
   });
});