<template>
    <div>
        <div class="row">
            <div class="col-2" style="margin-bottom: 15px;">
                <button class="btn btn-block btn-lg btn-dark" v-on:click="chooseInput('')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="col-2" v-for="icon in icons" style="margin-bottom: 15px;">
                <button class="btn btn-block btn-lg btn-outline-dark" v-on:click="chooseInput(icon)">
                    <i :class="icon"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "iconChoose",
        data() {
            return {
                icons: [
                    'fas fa-phone-square-alt',
                    'fas fa-phone-alt',
                    'fas fa-envelope',
                    'fas fa-envelope-open',
                    'fab fa-instagram',
                    'fab fa-facebook',
                    'fab fa-facebook-messenger',
                    'fab fa-facebook-f',
                    'fab fa-facebook-square',
                    'fab fa-whatsapp',
                    'fab fa-whatsapp-square',
                    'fab fa-snapchat',
                    'fab fa-snapchat-ghost',
                    'fab fa-snapchat-square',
                    'fab fa-google',
                    'fab fa-youtube',
                    'fab fa-tumblr',
                    'fab fa-tumblr-square',
                    'fab fa-twitter',
                    'fab fa-twitter-square',
                    'fas fa-hashtag',
                    'fab fa-pinterest',
                    'fab fa-pinterest-square',
                    'fab fa-linkedin-in',
                    'fab fa-linkedin',
                    'fab fa-discord',
                    'fab fa-vk',
                    'fab fa-telegram',
                    'fab fa-telegram',
                    'fab fa-telegram-plane',
                    'fab fa-skype',
                    'fab fa-viber',
                ]
            }
        },
        methods: {
            chooseInput(icon) {
                this.$emit('input', icon)
            }
        }
    }
</script>

<style scoped>

</style>