<template>
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <br>
            <div class="card">
                <div class="card-body">
                    <line-chart :data="chartData" :height="300"></line-chart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Line from './AnalyticsPage/Line'

    export default {
        name: "AnalyticsPage",
        props: ['id'],
        components: {
            'line-chart': Line
        },
        created () {
            this.loadData();
        },
        data() {
            return {
                chartData: {
                    labels: this.getLabels(),
                    datasets: [],
                    height: 300,
                }
            }
        },
        methods: {
            loadData() {
                this.chartData = {
                    labels: this.getLabels(),
                    datasets: [
                        {
                            label: 'Page Visits',
                            backgroundColor: '#007bff',
                            data: [1,0,0,0,0,1,3]
                        },
                    ],
                    height: 300,
                };
            },
            getLabels() {
                let labels = [];
                for (let i = 6; i >= 0; i--)
                {
                    let d = new Date();
                    let dateOffset = (24*60*60*1000) * i;
                    d.setTime(d.getTime() - dateOffset);
                    let label = d.getUTCDate() + '.' + d.getUTCMonth() + '.' + d.getUTCFullYear();
                    labels.push(label);

                }
                return labels;
            }
        }
    }
</script>

<style scoped>

</style>