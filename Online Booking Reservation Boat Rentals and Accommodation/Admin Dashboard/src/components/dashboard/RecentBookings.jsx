import React from 'react'
import { format } from 'date-fns'

const RecentBookings = () => {
  const bookings = [
    {
      id: 'B-1234',
      customer: 'John Smith',
      type: 'Boat',
      item: 'Sunset Cruiser',
      date: new Date(2023, 6, 28),
      status: 'Confirmed',
      amount: 350,
    },
    {
      id: 'A-5678',
      customer: 'Emily Johnson',
      type: 'Accommodation',
      item: 'Beach Villa',
      date: new Date(2023, 6, 30),
      status: 'Pending',
      amount: 1200,
    },
    {
      id: 'B-9012',
      customer: 'Michael Brown',
      type: 'Boat',
      item: 'Fishing Boat',
      date: new Date(2023, 7, 2),
      status: 'Confirmed',
      amount: 275,
    },
    {
      id: 'A-3456',
      customer: 'Sarah Wilson',
      type: 'Accommodation',
      item: 'Ocean View Suite',
      date: new Date(2023, 7, 5),
      status: 'Cancelled',
      amount: 850,
    },
    {
      id: 'B-7890',
      customer: 'David Lee',
      type: 'Boat',
      item: 'Luxury Yacht',
      date: new Date(2023, 7, 8),
      status: 'Confirmed',
      amount: 1500,
    },
  ]

  const getStatusBadgeClass = (status) => {
    switch (status) {
      case 'Confirmed':
        return 'badge-success'
      case 'Pending':
        return 'badge-warning'
      case 'Cancelled':
        return 'badge-danger'
      default:
        return 'badge-info'
    }
  }

  return (
    <div className="card h-full">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-medium text-gray-900">Recent Bookings</h3>
        <button className="text-sm font-medium text-primary hover:text-primary-dark">
          View all
        </button>
      </div>
      
      <div className="overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {bookings.map((booking) => (
              <tr key={booking.id} className="hover:bg-gray-50">
                <td className="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{booking.id}</td>
                <td className="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{booking.customer}</td>
                <td className="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{booking.type}</td>
                <td className="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{booking.item}</td>
                <td className="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{format(booking.date, 'MMM d, yyyy')}</td>
                <td className="px-3 py-2 whitespace-nowrap">
                  <span className={`badge ${getStatusBadgeClass(booking.status)}`}>
                    {booking.status}
                  </span>
                </td>
                <td className="px-3 py-2 whitespace-nowrap text-sm text-gray-700">${booking.amount}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default RecentBookings
