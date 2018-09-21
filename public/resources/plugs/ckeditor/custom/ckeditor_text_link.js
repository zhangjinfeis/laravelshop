/**
 * 用于文字编辑
 * @param config
 */

CKEDITOR.editorConfig = function( config ) {
    config.uiColor = '#ffffff';
    config.removePlugins = 'elementspath,resize';
    config.toolbarGroups = [
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'clipboard', groups: [ 'undo', 'clipboard' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        { name: 'colors', groups: [ 'colors' ] },
        '/',
        { name: 'styles', groups: [ 'styles' ] },

        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
    ];

    config.removeButtons = 'Source,Save,NewPage,Preview,Print,Templates,Cut,Copy,PasteText,Paste,PasteFromWord,CopyFormatting,RemoveFormat,Subscript,Superscript,Find,Replace,CreateDiv,BidiLtr,BidiRtl,Language,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Select,Button,HiddenField,ImageButton,Textarea,Anchor,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,Font,FontSize,Maximize,ShowBlocks,About,BGColor';
};