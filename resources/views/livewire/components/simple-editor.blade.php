
<div 
    x-data="{ 
        content: @entangle('content').defer,
        executeCommand(command) {
            document.execCommand(command, false, null);
            this.$refs.editor.focus();
            this.updateContent();
        },
        updateContent() {
            this.content = this.$refs.editor.innerHTML;
            @this.call('contentUpdated', this.$refs.editor.innerHTML);
        },
        init() {
            this.$refs.editor.innerHTML = this.content;
            this.$refs.editor.addEventListener('input', () => this.updateContent());
            this.$refs.editor.addEventListener('paste', (e) => {
                e.preventDefault();
                const text = e.clipboardData.getData('text/plain');
                document.execCommand('insertText', false, text);
            });
        }
    }"
    class="w-full"
>
    <!-- Toolbar -->
    <div class="flex items-center space-x-2 p-2 bg-gray-50 border border-gray-300 rounded-t-lg">
        <button 
            @click="executeCommand('bold')"
            type="button"
            class="p-1 hover:bg-gray-200 rounded" 
            title="Bold"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M12.5,5.5V5c0-0.3-0.2-0.5-0.5-0.5H5.5C5.2,4.5,5,4.7,5,5v1c0,0.3,0.2,0.5,0.5,0.5h2v7h-2c-0.3,0-0.5,0.2-0.5,0.5v1 c0,0.3,0.2,0.5,0.5,0.5h6.5c0.3,0,0.5-0.2,0.5-0.5v-1c0-0.3-0.2-0.5-0.5-0.5h-2v-7h2C12.3,6,12.5,5.8,12.5,5.5z"/>
            </svg>
        </button>
        <button 
            @click="executeCommand('italic')"
            type="button"
            class="p-1 hover:bg-gray-200 rounded"
            title="Italic"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M15,4H8.4l-0.4,2h2.3l-2.6,8H5l-0.4,2h6.6l0.4-2H9.3l2.6-8H15L15,4z"/>
            </svg>
        </button>
        <div class="w-px h-5 bg-gray-300"></div>
        <button 
            @click="executeCommand('insertUnorderedList')"
            type="button"
            class="p-1 hover:bg-gray-200 rounded"
            title="Bullet List"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                <path fillRule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h3a1 1 0 100-2H9z" clipRule="evenodd"/>
            </svg>
        </button>
        <button 
            @click="executeCommand('insertOrderedList')"
            type="button"
            class="p-1 hover:bg-gray-200 rounded"
            title="Numbered List"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd"/>
            </svg>
        </button>
    </div>

    <!-- Editor Content -->
    <div
        x-ref="editor"
        contenteditable="true"
        class="w-full min-h-[200px] p-3 border border-t-0 border-gray-300 rounded-b-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        placeholder="Write your tasks here..."
        wire:ignore
    ></div>
</div>