import { Controller } from "@hotwired/stimulus";
import ApexCharts from "apexcharts";

// Connects to data-controller="barchart"
export default class extends Controller {
    connect() {
        const useroptions =
            JSON.parse(this.element.dataset.config || "{}") || {};
        console.log(this.element.dataset.options);
        const options = Object.assign(
            {
                series: [
                    {
                        name: "Inflation",
                        data: [
                            2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8,
                            0.5, 0.2,
                        ],
                    },
                ],
                chart: {
                    height: 350,
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: "top", // top, center, bottom
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetY: -20,
                    style: {
                        fontSize: "12px",
                        colors: ["#304758"],
                    },
                },

                xaxis: {
                    categories: [
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    position: "top",
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    crosshairs: {
                        fill: {
                            type: "gradient",
                            gradient: {
                                colorFrom: "#D8E3F0",
                                colorTo: "#BED1E6",
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                    },
                },
                yaxis: {
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: false,
                        formatter: function (val) {
                            return val + "%";
                        },
                    },
                },
                title: {
                    text: "Inflation in Zimbabwe 2023",
                    floating: true,
                    offsetY: 330,
                    align: "center",
                    style: {
                        color: "#444",
                    },
                },
            },
            useroptions,
        );

        var chart = new ApexCharts(this.element, options);
        chart.render();
    }
}
