<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Manage Cosec Templates</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Form Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Credit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Active</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($templates as $template)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->form_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->credit_cost }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    @if($template->is_active)
                                        <span class="text-green-600">Yes</span>
                                    @else
                                        <span class="text-red-600">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    <button wire:click="edit({{ $template->id }})" class="btn border-primary text-primary hover:bg-primary hover:text-white">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($editingId)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Template</h3>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name</label>
                <input type="text" class="form-input" wire:model="name">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Content</label>
                <div wire:ignore>
                    {{-- <div class="editor-container editor-container_document-editor editor-container_include-fullscreen" id="editor-container">
                        <div class="editor-container__toolbar" id="editor-toolbar"></div>
                        <div class="editor-container__editor-wrapper">
                            <div class="editor-container__editor">
                                <textarea id="content" name="content" class="form-input" wire:model="content" rows="10"></textarea>
                            </div>
                        </div>
                    </div> --}}
                    <textarea id="content" name="content" class="form-input" wire:model="content" rows="10"></textarea>
                </div>
                {{-- <livewire:quill-text-editor class="form-input" wire:model="content" theme="snow" /> --}}
                @error('content') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Credit Cost</label>
                <input type="number" class="form-input" wire:model="credit_cost" min="0">
                @error('credit_cost') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="is_active" class="form-checkbox">
                    <span class="ml-2 text-sm font-medium text-gray-800">Active</span>
                </label>
            </div>

            <div class="flex gap-2">
                <button wire:click="save" class="btn bg-success text-white" id="ckeditor-save">Save</button>
                <button wire:click="cancel" class="btn bg-gray-500 text-white">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>


@livewireScripts
<script>
const {
    ClassicEditor,
    Autosave,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Autoformat,
    TextTransformation,
    Underline,
    Strikethrough,
    Subscript,
    Superscript,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Highlight,
    Heading,
    BlockQuote,
    HorizontalLine,
    Indent,
    IndentBlock,
    Alignment,
    List,
    Table,
    TableToolbar,
    ShowBlocks,
    SourceEditing,
    GeneralHtmlSupport,
    HtmlComment
} = window.CKEDITOR;

const LICENSE_KEY =
    'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjIyMTQzOTksImp0aSI6ImFkZGJlZDIzLTIyNDItNDc2Ny1iMmQ2LWYxMGU3ZTQyODFlZSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImFmZWI1Yjc3In0.cYrK1D-ygTkL6sr3P_eBmc7XbivWZKGq4m6mDP73EclQNn9-oDlG56w2YnxG0HdvBQ7IPpSnCUcqdpAI9bGaNA';

const editorConfig = {
    toolbar: {
        items: [
            'undo',
            'redo',
            '|',
            'sourceEditing',
            'showBlocks',
            '|',
            'heading',
            '|',
            'fontSize',
            'fontFamily',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'subscript',
            'superscript',
            '|',
            'horizontalLine',
            'insertTable',
            'highlight',
            'blockQuote',
            '|',
            'alignment',
            '|',
            'bulletedList',
            'numberedList',
            'outdent',
            'indent'
        ],
        shouldNotGroupWhenFull: false
    },
    plugins: [
        Alignment,
        Autoformat,
        Autosave,
        BlockQuote,
        Bold,
        Essentials,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        GeneralHtmlSupport,
        Heading,
        Highlight,
        HorizontalLine,
        HtmlComment,
        Indent,
        IndentBlock,
        Italic,
        List,
        Paragraph,
        ShowBlocks,
        SourceEditing,
        Strikethrough,
        Subscript,
        Superscript,
        Table,
        TableToolbar,
        TextTransformation,
        Underline
    ],
    fontFamily: {
        supportAllValues: true
    },
    fontSize: {
        options: [10, 12, 14, 'default', 18, 20, 22],
        supportAllValues: true
    },
    heading: {
        options: [
            {
                model: 'paragraph',
                title: 'Paragraph',
                class: 'ck-heading_paragraph'
            },
            {
                model: 'heading1',
                view: 'h1',
                title: 'Heading 1',
                class: 'ck-heading_heading1'
            },
            {
                model: 'heading2',
                view: 'h2',
                title: 'Heading 2',
                class: 'ck-heading_heading2'
            },
            {
                model: 'heading3',
                view: 'h3',
                title: 'Heading 3',
                class: 'ck-heading_heading3'
            },
            {
                model: 'heading4',
                view: 'h4',
                title: 'Heading 4',
                class: 'ck-heading_heading4'
            },
            {
                model: 'heading5',
                view: 'h5',
                title: 'Heading 5',
                class: 'ck-heading_heading5'
            },
            {
                model: 'heading6',
                view: 'h6',
                title: 'Heading 6',
                class: 'ck-heading_heading6'
            }
        ]
    },
    htmlSupport: {
        allow: [
            {
                name: /^.*$/,
                styles: true,
                attributes: true,
                classes: true
            }
        ]
    },
    licenseKey: LICENSE_KEY,
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    }
};

ClassicEditor.create(document.querySelector('#content'), editorConfig);
</script>

{{-- <script>
const {
    DecoupledEditor,
    Autosave,
    Essentials,
    Paragraph,
    Autoformat,
    TextTransformation,
    ImageUtils,
    ImageEditing,
    Heading,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Subscript,
    Superscript,
    Code,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Indent,
    IndentBlock,
    Alignment,
    Link,
    AutoLink,
    HorizontalLine,
    List,
    TodoList,
    Table,
    TableToolbar,
    Fullscreen,
    BlockQuote,
    HtmlComment
} = window.CKEDITOR;

const LICENSE_KEY =
    'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjIyMTQzOTksImp0aSI6ImFkZGJlZDIzLTIyNDItNDc2Ny1iMmQ2LWYxMGU3ZTQyODFlZSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImFmZWI1Yjc3In0.cYrK1D-ygTkL6sr3P_eBmc7XbivWZKGq4m6mDP73EclQNn9-oDlG56w2YnxG0HdvBQ7IPpSnCUcqdpAI9bGaNA';

const editorConfig = {
    toolbar: {
        items: [
            'undo',
            'redo',
            '|',
            'fullscreen',
            '|',
            'heading',
            '|',
            'fontSize',
            'fontFamily',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'subscript',
            'superscript',
            'code',
            '|',
            'horizontalLine',
            'link',
            'insertTable',
            'blockQuote',
            '|',
            'alignment',
            '|',
            'bulletedList',
            'numberedList',
            'todoList',
            'outdent',
            'indent'
        ],
        shouldNotGroupWhenFull: false
    },
    plugins: [
        Alignment,
        Autoformat,
        AutoLink,
        Autosave,
        BlockQuote,
        Bold,
        Code,
        Essentials,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        Fullscreen,
        Heading,
        HorizontalLine,
        HtmlComment,
        ImageEditing,
        ImageUtils,
        Indent,
        IndentBlock,
        Italic,
        Link,
        List,
        Paragraph,
        Strikethrough,
        Subscript,
        Superscript,
        Table,
        TableToolbar,
        TextTransformation,
        TodoList,
        Underline
    ],
    fontFamily: {
        supportAllValues: true
    },
    fontSize: {
        options: [10, 12, 14, 'default', 18, 20, 22],
        supportAllValues: true
    },
    fullscreen: {
        onEnterCallback: container =>
            container.classList.add(
                'editor-container',
                'editor-container_document-editor',
                'editor-container_include-fullscreen',
                'main-container'
            )
    },
    heading: {
        options: [
            {
                model: 'paragraph',
                title: 'Paragraph',
                class: 'ck-heading_paragraph'
            },
            {
                model: 'heading1',
                view: 'h1',
                title: 'Heading 1',
                class: 'ck-heading_heading1'
            },
            {
                model: 'heading2',
                view: 'h2',
                title: 'Heading 2',
                class: 'ck-heading_heading2'
            },
            {
                model: 'heading3',
                view: 'h3',
                title: 'Heading 3',
                class: 'ck-heading_heading3'
            },
            {
                model: 'heading4',
                view: 'h4',
                title: 'Heading 4',
                class: 'ck-heading_heading4'
            },
            {
                model: 'heading5',
                view: 'h5',
                title: 'Heading 5',
                class: 'ck-heading_heading5'
            },
            {
                model: 'heading6',
                view: 'h6',
                title: 'Heading 6',
                class: 'ck-heading_heading6'
            }
        ]
    },
    licenseKey: LICENSE_KEY,
    link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
            toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        }
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    }
};

DecoupledEditor.create(document.querySelector('#content'), editorConfig).then(editor => {
    document.querySelector('#editor-toolbar').appendChild(editor.ui.view.toolbar.element);

    return editor;
});
</script> --}}
