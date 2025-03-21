import React from 'react'
import { Line } from 'react-chartjs-2'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js'

// Register ChartJS components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

const RevenueChart = () => {
  const data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label: 'Boat Rentals',
        data: [12000, 19000, 25000, 32000, 39000, 45000, 52000, 59000, 48000, 35000, 28000, 21000],
        borderColor: '#0080ff',
        backgroundColor: 'rgba(0, 128, 255, 0.1)',
        tension: 0.4,
        fill: true,
      },
      {
        label: 'Accommodations',
        data: [15000, 22000, 28000, 35000, 42000, 49000, 56000, 63000, 52000, 38000, 31000, 24000],
        borderColor: '#66cc99',
        backgroundColor: 'rgba(102, 204, 153, 0.1)',
        tension: 0.4,
        fill: true,
      },
    ],
  }

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: false,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: (value) => `$${value.toLocaleString()}`,
        },
      },
    },
    interaction: {
      mode: 'index',
      intersect: false,
    },
  }

  return (
    <div className="card h-full">
      <h3 className="text-lg font-medium text-gray-900 mb-4">Revenue Overview</h3>
      <Line data={data} options={options} />
    </div>
  )
}

export default RevenueChart
