document.addEventListener('DOMContentLoaded', function () {
    const roots = document.querySelectorAll('[data-tab-root]');

    roots.forEach(function (root) {
        const tabs = Array.from(root.querySelectorAll('[role="tab"]'));
        if (tabs.length === 0) {
            return;
        }

        const activateTab = function (activeTab) {
            tabs.forEach(function (tab) {
                const selected = tab === activeTab;
                tab.setAttribute('aria-selected', selected ? 'true' : 'false');
                tab.setAttribute('tabindex', selected ? '0' : '-1');

                const panelId = tab.getAttribute('aria-controls');
                if (!panelId) {
                    return;
                }

                const panel = document.getElementById(panelId);
                if (!panel) {
                    return;
                }

                panel.hidden = !selected;
            });
        };

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activateTab(tab);
            });
        });
    });
});
