<template>
    <div>
        <div class="card">
            <div class="card-header">
                <button type="submit" class="btn btn-default btn-outline-dark" v-on:click="backCallback">Back</button>
            </div>
            <div class="card-body">
                <div class="row">
                <button class="btn btn-lg col-6 col-lg-4" v-on:click="toLinkFormCallback">
                    <i class="fas fa-link"></i><br>
                    Add Link
                </button>
                <button class="btn btn-lg col-6 col-lg-4" v-on:click="toTextFormCallback">
                    <i class="fas fa-align-left"></i><br>
                    Add Text
                </button>
                <button class="btn btn-lg col-6 col-lg-4" v-on:click="toLinkFormCallback">
                    <i class="fas fa-image"></i><br>
                    Add Image
                </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "addBlock",
        props: {
            backCallback: {
                type: Function
            },
            toLinkFormCallback: {
                type: Function
            },
            toTextFormCallback: {
                type: Function
            },
        }
    }
</script>