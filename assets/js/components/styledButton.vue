<template>
    <button class="btn btn-lg btn-block styled-button-style"
            :style="{
                '--border-color': borderColor,
                '--background-color': backgroundColor,
                '--text-color': textColor,
                '--hover-border-color': hoverBorderColor,
                '--hover-background-color': hoverBackgroundColor,
                '--hover-text-color': hoverTextColor,
                '--border-radius': borderRadius,
                '--hover-opacity': hoverOpacity,
                marginTop: '8px',
            }"
            v-on:click="clickCallback({}, link)"
            v-html="getLinkText(link)"></button>
</template>

<script>
    const BUTTON_STYLE_DEFAULT = 1;
    const BUTTON_STYLE_OUTLINE_DEFAULT = 2;
    const BUTTON_STYLE_SQUARE = 3;
    const BUTTON_STYLE_OUTLINE_SQUARE = 4;
    const BUTTON_STYLE_ROUND = 5;
    const BUTTON_STYLE_OUTLINE_ROUND = 6;

    export default {
        props: {
            link: {
                type: Object
            },
            styleId: {
                type: Number
            },
            clickCallback: {
                type: Function
            }
        },
        name: "styledButton",
        computed: {
            borderColor() {
                if (this.link.hasOwnProperty('jsonSettings')) {
                    return this.link.jsonSettings.backgroundColor
                }
                return  this.link.color;
            },
            backgroundColor() {
                if (this.isOutline) {
                    return 'rgba(255, 255, 255, 0)';
                }

                return this.borderColor;
            },
            textColor() {
                if (this.isOutline) {
                    return this.borderColor;
                }

                if (this.link.hasOwnProperty('jsonSettings')) {
                    return this.link.jsonSettings.textColor
                }
                return this.link.textColor;
            },
            hoverBorderColor() {
                return this.borderColor;
            },
            hoverBackgroundColor() {
                return this.borderColor;
            },
            hoverTextColor() {
                if (this.link.hasOwnProperty('jsonSettings')) {
                    return this.link.jsonSettings.textColor
                }
                return this.link.textColor;
            },
            isOutline() {
                return [BUTTON_STYLE_OUTLINE_DEFAULT, BUTTON_STYLE_OUTLINE_SQUARE, BUTTON_STYLE_OUTLINE_ROUND].includes(this.styleId);
            },
            borderRadius() {
                if ([BUTTON_STYLE_SQUARE, BUTTON_STYLE_OUTLINE_SQUARE].includes(this.styleId)) {
                    return '0px';
                } else if ([BUTTON_STYLE_ROUND, BUTTON_STYLE_OUTLINE_ROUND].includes(this.styleId)) {
                    return '100px';
                }

                return '0.3rem';
            },
            hoverOpacity() {
                if (!this.isOutline) {
                    return '0.8';
                }

                return '1.0';
            }
        },
        methods: {
            getLinkText(link) {
                let icon = '';
                let title = '';
                if (link.hasOwnProperty('icon') && link.icon.length) {
                    icon = `<i class="${link.icon}"></i>`;
                } else if (link.hasOwnProperty('jsonSettings') && link.jsonSettings.hasOwnProperty('icon') && link.jsonSettings.icon.length) {
                    icon = `<i class="${link.jsonSettings.icon}"></i>`;
                }

                if (link.hasOwnProperty('title') && link.title.length) {
                    title = link.title;
                } else if (link.hasOwnProperty('jsonSettings') && link.jsonSettings.hasOwnProperty('title') && link.jsonSettings.title.length) {
                    title = link.jsonSettings.title;
                }

                return `${icon} ${title}`;
            }
        }
    }
</script>

<style scoped>
    .styled-button-style {
        border-color: var(--border-color);
        background-color: var(--background-color);
        color: var(--text-color);
        border-radius: var(--border-radius);
    }

    .styled-button-style:hover {
        border-color: var(--hover-border-color);
        background-color: var(--hover-background-color);
        color: var(--hover-text-color);
        opacity: var(--hover-opacity);
    }
</style>