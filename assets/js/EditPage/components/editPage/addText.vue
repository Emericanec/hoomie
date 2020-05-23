<template>
    <div>
        <div class="card">
            <div class="card-header">
                <button type="submit" class="btn btn-default btn-outline-dark" v-on:click="backCallback">Back</button>
                <button type="submit" class="btn btn-primary float-right" v-on:click="backCallback">Save</button>
            </div>
            <div class="card-body">
                <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
                    <div>
                        <button class="btn" :class="{ 'is-active': isActive.bold() }" @click="commands.bold">
                            <i class="fas fa-bold"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.italic() }" @click="commands.italic">
                            <i class="fas fa-italic"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.strike() }" @click="commands.strike">
                            <i class="fas fa-strikethrough"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.underline() }" @click="commands.underline">
                            <i class="fas fa-underline"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                                @click="commands.heading({ level: 1 })">
                            H1
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                                @click="commands.heading({ level: 2 })">
                            H2
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                                @click="commands.heading({ level: 3 })">
                            H3
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.bullet_list() }"
                                @click="commands.bullet_list">
                            <i class="fas fa-list-ul"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.ordered_list() }"
                                @click="commands.ordered_list">
                            <i class="fas fa-list-ol"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.blockquote() }"
                                @click="commands.blockquote">
                            <i class="fas fa-quote-left"/>
                        </button>

                        <button class="btn" :class="{ 'is-active': isActive.code_block() }"
                                @click="commands.code_block">
                            <i class="fas fa-code"/>
                        </button>

                        <button class="btn" @click="commands.undo">
                            <i class="fas fa-undo"/>
                        </button>

                        <button class="btn" @click="commands.redo">
                            <i class="fas fa-redo"/>
                        </button>
                    </div>
                </editor-menu-bar>
                <editor-content class="editor" :editor="editor"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap'
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
    } from 'tiptap-extensions';

    export default {
        name: "addText",
        props: {
            backCallback: {
                type: Function
            },
        },
        data() {
            return {
                editor: null,
            }
        },
        mounted() {
            this.editor = new Editor({
                extensions: [
                    new Blockquote(),
                    new CodeBlock(),
                    new HardBreak(),
                    new Heading({levels: [1, 2, 3]}),
                    new BulletList(),
                    new OrderedList(),
                    new ListItem(),
                    new TodoItem(),
                    new TodoList(),
                    new Bold(),
                    new Code(),
                    new Italic(),
                    new Link(),
                    new Strike(),
                    new Underline(),
                    new History(),
                ],
                content: '',
            })
        },
        beforeDestroy() {
            this.editor.destroy()
        },
        components: {
            EditorContent,
            EditorMenuBar,
        },
    }
</script>

<style scoped>
    .editor{
        border: 1px solid;
        padding: 10px;
    }
</style>

<style>
    div:focus {
        outline: none
    }
</style>