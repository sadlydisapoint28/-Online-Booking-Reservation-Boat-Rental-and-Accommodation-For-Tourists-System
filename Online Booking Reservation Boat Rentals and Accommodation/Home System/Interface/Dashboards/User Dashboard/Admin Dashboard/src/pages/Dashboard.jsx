import React from 'react'
import { FiUsers, FiCalendar, FiDollarSign, FiTrendingUp } from 'react-icons/fi'
import StatCard from '../components/dashboard/StatCard'
import RevenueChart from '../components/dashboard/RevenueChart'
import BookingCalendar from '../components/dashboard/BookingCalendar'
import RecentBookings from '../components/dashboard/RecentBookings'
import PopularItems from '../components/dashboard/PopularItems'

const Dashboard = () => {
  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard 
          title="Total Bookings" 
          value="1,248" 
          icon={<FiCalendar className="h-6 w-6 text-primary" />}
          change="+12.5%" 
          changeType="increase" 
        />
        <StatCard 
          title="Total Customers" 
          value="854" 
          icon={<FiUsers className="h-6 w-6 text-primary" />}
          change="+8.2%" 
          changeType="increase" 
        />
        <StatCard 
          title="Monthly Revenue" 
          value="$86,589" 
          icon={<FiDollarSign className="h-6 w-6 text-primary" />}
          change="+15.3%" 
          changeType="increase" 
        />
        <StatCard 
          title="Occupancy Rate" 
          value="78%" 
          icon={<FiTrendingUp className="h-6 w-6 text-primary" />}
          change="-2.1%" 
          changeType="decrease" 
        />
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <RevenueChart />
        </div>
        <div>
          <BookingCalendar />
        </div>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <RecentBookings />
        </div>
        <div>
          <PopularItems />
        </div>
      </div>
    </div>
  )
}

export default Dashboard
