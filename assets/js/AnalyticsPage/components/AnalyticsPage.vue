<template>
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <br>
            <div class="card">
                <div class="card-header">
                    <h3>Statistics for the last 7 days:</h3>
                </div>
                <div class="card-body">
                    <line-chart :chart-data="chartData" :options="chartOptions"></line-chart>
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
        created() {
            this.loadData();
        },
        computed: {
            chartOptions() {
                return {
                    maintainAspectRatio: false
                }
            }
        },
        data() {
            return {
                chartData: {
                    labels: [],
                    datasets: [],
                    height: 300,
                }
            }
        },
        methods: {
            loadData() {
                this.$http.get('/api/analytics/all').then(response => {
                    this.chartData = {
                        labels: this.getLabels(response.body),
                        datasets: [
                            {
                                label: 'Page Visits',
                                backgroundColor: '#007bff',
                                data: this.getDataSet(response.body)
                            },
                        ],
                        height: 300,
                    };
                });
            },
            getLabels(response) {
                return response.map(row => row.label);
            },
            getDataSet(response) {
                return response.map(row => row.count);
            }
        }
    }
</script>

<style scoped>

</style>