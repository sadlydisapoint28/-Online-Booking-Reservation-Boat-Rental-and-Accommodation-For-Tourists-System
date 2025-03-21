import React from 'react'

const StatCard = ({ title, value, icon, change, changeType }) => {
  return (
    <div className="card">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm font-medium text-gray-500">{title}</p>
          <p className="mt-1 text-2xl font-semibold text-gray-900">{value}</p>
        </div>
        <div className={`p-3 rounded-full bg-${changeType === 'increase' ? 'green' : 'red'}-100`}>
          {icon}
        </div>
      </div>
      
      <div className="mt-4 flex items-center">
        <span className={`text-sm font-medium ${
          changeType === 'increase' ? 'text-green-600' : 'text-red-600'
        }`}>
          {change}
        </span>
        <span className="text-sm text-gray-500 ml-2">from last month</span>
      </div>
    </div>
  )
}

export default StatCard
