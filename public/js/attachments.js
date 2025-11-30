document.addEventListener('DOMContentLoaded', () => {
    const maxAttachments = 10;

    function setupAttachmentFieldset(wrapperId, addButtonId) {
        const wrapper = document.getElementById(wrapperId);
        const addButton = document.getElementById(addButtonId);
        if (!wrapper || !addButton) return;

        const updateState = () => {
            const inputs = wrapper.querySelectorAll('input[type="file"][name="attachments[]"]');
            if (inputs.length >= maxAttachments) {
                addButton.disabled = true;
                addButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        };

        const addField = () => {
            const inputs = wrapper.querySelectorAll('input[type="file"][name="attachments[]"]');
            if (inputs.length >= maxAttachments) return;

            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 mt-1';

            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'attachments[]';
            input.className = 'block w-full text-xs sm:text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'shrink-0 rounded-full bg-slate-700 px-2 py-1 text-[10px] text-slate-100 hover:bg-slate-600';
            removeBtn.textContent = '削除';
            removeBtn.addEventListener('click', () => {
                div.remove();
                updateState();
            });

            div.appendChild(input);
            div.appendChild(removeBtn);
            wrapper.appendChild(div);

            updateState();
        };

        addButton.addEventListener('click', (e) => {
            e.preventDefault();
            addField();
        });

        // 初期入力が1つもない場合は1つ追加
        if (wrapper.querySelectorAll('input[type="file"][name="attachments[]"]').length === 0) {
            addField();
        } else {
            updateState();
        }
    }

    setupAttachmentFieldset('post-attachments-wrapper', 'post-attachments-add');
    setupAttachmentFieldset('post-edit-attachments-wrapper', 'post-edit-attachments-add');
    setupAttachmentFieldset('reply-attachments-wrapper', 'reply-attachments-add');
});
