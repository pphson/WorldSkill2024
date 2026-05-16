// Xây dựng Web Component tùy chỉnh cho cấu trúc Thẻ Tab theo chuẩn W3C ARIA
const tabsData = [
    { id: "1", title: "Tab 1", content: "This is the content for Tab 1." },
    { id: "2", title: "Tab 2", content: "This is the content for Tab 2." },
    { id: "3", title: "Tab 3", content: "This is the content for Tab 3." }
];

class CustomTabs extends HTMLElement {
    connectedCallback() {
        this.render();
        this.initTabs();
    }

    render() {
        let tabListHtml = `<div class="tab-list" role="tablist" aria-label="More Information">`;
        let tabContentHtml = ``;

        tabsData.forEach((tab, index) => {
            const isSelected = index === 0;
            tabListHtml += `
                <button role="tab" 
                        aria-selected="${isSelected}" 
                        aria-controls="panel-${tab.id}" 
                        id="tab-${tab.id}" 
                        tabindex="${isSelected ? '0' : '-1'}" 
                        class="tab-btn ${isSelected ? 'active' : ''}">
                    ${tab.title}
                </button>`;

            tabContentHtml += `
                <div id="panel-${tab.id}" 
                     role="tabpanel" 
                     aria-labelledby="tab-${tab.id}" 
                     aria-hidden="${!isSelected}" 
                     class="tab-panel" ${!isSelected ? 'hidden' : ''}>
                    <p>${tab.content}</p>
                </div>`;
        });

        tabListHtml += `</div>`;

        this.innerHTML = `
            <style>
                .tab-list { display: flex; gap: 10px; border-bottom: 2px solid #ccc; margin-bottom: 15px; }
                .tab-btn { padding: 10px 20px; cursor: pointer; background: #eee; border: none; }
                .tab-btn.active { background: #002f6c; color: #fff; }
                .tab-panel { padding: 10px; border: 1px solid #ccc; background: #fafafa; }
                .tab-panel[hidden] { display: none; }
            </style>
            ${tabListHtml}
            ${tabContentHtml}
        `;
    }

    initTabs() {
        const buttons = this.querySelectorAll('[role="tab"]');
        const panels = this.querySelectorAll('[role="tabpanel"]');

        buttons.forEach((btn, idx) => {
            btn.addEventListener('click', () => this.switchTab(idx, buttons, panels));
            
            // Điều hướng bằng bàn phím (Left / Right Arrows)
            btn.addEventListener('keydown', (e) => {
                let targetIdx = null;
                if (e.key === 'ArrowRight') {
                    targetIdx = (idx + 1) % buttons.length;
                } else if (e.key === 'ArrowLeft') {
                    targetIdx = (idx - 1 + buttons.length) % buttons.length;
                }

                if (targetIdx !== null) {
                    buttons[targetIdx].focus();
                    this.switchTab(targetIdx, buttons, panels);
                }
            });
        });
    }

    switchTab(index, buttons, panels) {
        buttons.forEach((btn, i) => {
            const isTarget = i === index;
            btn.setAttribute('aria-selected', isTarget);
            btn.setAttribute('tabindex', isTarget ? '0' : '-1');
            btn.classList.toggle('active', isTarget);
        });

        panels.forEach((panel, i) => {
            const isTarget = i === index;
            panel.setAttribute('aria-hidden', !isTarget);
            if (isTarget) {
                panel.removeAttribute('hidden');
            } else {
                panel.setAttribute('hidden', '');
            }
        });
    }
}

customElements.define('custom-tabs', CustomTabs);