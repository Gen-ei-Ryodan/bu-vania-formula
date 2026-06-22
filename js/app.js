(() => {
    const init = () => {
        const toggle = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('[data-sidebar]');
        const backdrop = document.querySelector('[data-sidebar-backdrop]');

        if (toggle && sidebar) {
            function openSidebar() {
                sidebar.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', () => {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            document.addEventListener('click', (e) => {
                const target = e.target;
                if (!(target instanceof HTMLElement)) {
                    return;
                }

                const clickedToggle = target.closest('[data-sidebar-toggle]');
                const clickedSidebar = target.closest('[data-sidebar]');
                if (!clickedToggle && !clickedSidebar) {
                    closeSidebar();
                }
            });
        }

        document.querySelectorAll('[data-repeatable]').forEach((root) => {
            if (!(root instanceof HTMLElement)) {
                return;
            }

            const template = root.querySelector('template');
            const list = root.querySelector('[data-repeatable-list]');
            const add = root.querySelector('[data-repeatable-add]');

            if (
                !(template instanceof HTMLTemplateElement) ||
                !(list instanceof HTMLElement) ||
                !(add instanceof HTMLElement)
            ) {
                return;
            }

            const refreshNames = () => {
                list.querySelectorAll('[data-repeatable-row]').forEach((row, index) => {
                    row.querySelectorAll('[data-name]').forEach((el) => {
                        if (!(el instanceof HTMLInputElement) && !(el instanceof HTMLSelectElement)) {
                            return;
                        }
                        const base = el.getAttribute('data-name');
                        if (!base) {
                            return;
                        }
                        el.name = `items[${index}][${base}]`;
                    });
                });
            };

            const addRow = () => {
                const node = template.content.firstElementChild;
                if (!(node instanceof HTMLElement)) {
                    return;
                }
                const clone = node.cloneNode(true);
                if (!(clone instanceof HTMLElement)) {
                    return;
                }

                clone.querySelectorAll('[data-repeatable-remove]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        clone.remove();
                        refreshNames();
                    });
                });

                list.appendChild(clone);
                refreshNames();
            };

            add.addEventListener('click', (e) => {
                e.preventDefault();
                addRow();
            });

            if (list.querySelectorAll('[data-repeatable-row]').length === 0) {
                addRow();
            } else {
                refreshNames();
                list.querySelectorAll('[data-repeatable-row]').forEach((row) => {
                    row.querySelectorAll('[data-repeatable-remove]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            row.remove();
                            refreshNames();
                        });
                    });
                });
            }
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
