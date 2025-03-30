import React, { useState } from 'react'
import { FiFilter, FiSearch, FiPlus, FiEdit2, FiEye, FiTrash2 } from 'react-icons/fi'
import { format } from 'date-fns'

const Bookings = () => {
  const [filterOpen, setFilterOpen] = useState(false)
  const [selectedStatus, setSelectedStatus] = useState('All')
  
  const bookings = [
    {
      id: 'B-1234',
      customer: 'John Smith',
      email: 'john.smith@example.com',
      phone: '+1 (555) 123-4567',
      type: 'Boat',
      item: 'Sunset Cruiser',
      startDate: new Date(2023, 6, 28),
      endDate: new Date(2023, 6, 29),
      status: 'Confirmed',
      amount: 350,
      paymentStatus: 'Paid',
    },
    {
      id: 'A-5678',
      customer: 'Emily Johnson',
      email: 'emily.johnson@example.com',
      phone: '+1 (555) 234-5678',
      type: 'Accommodation',
      item: 'Beach Villa',
      startDate: new Date(2023, 6, 30),
      endDate: new Date(2023, 7, 6),
      status: 'Pending',
      amount: 1200,
      paymentStatus: 'Partial',
    },
    {
      id: 'B-9012',
      customer: 'Michael Brown',
      email: 'michael.brown@example.com',
      phone: '+1 (555) 345-6789',
      type: 'Boat',
      item: 'Fishing Boat',
      startDate: new Date(2023, 7, 2),
      endDate: new Date(2023, 7, 2),
      status: 'Confirmed',
      amount: 275,
      paymentStatus: 'Paid',
    },
    {
      id: 'A-3456',
      customer: 'Sarah Wilson',
      email: 'sarah.wilson@example.com',
      phone: '+1 (555) 456-7890',
      type: 'Accommodation',
      item: 'Ocean View Suite',
      startDate: new Date(2023, 7, 5),
      endDate: new Date(2023, 7, 10),
      status: 'Cancelled',
      amount: 850,
      paymentStatus: 'Refunded',
    },
    {
      id: 'B-7890',
      customer: 'David Lee',
      email: 'david.lee@example.com',
      phone: '+1 (555) 567-8901',
      type: 'Boat',
      item: 'Luxury Yacht',
      startDate: new Date(2023, 7, 8),
      endDate: new Date(2023, 7, 9),
      status: 'Confirmed',
      amount: 1500,
      paymentStatus: 'Paid',
    },
    {
      id: 'A-1234',
      customer: 'Jessica Taylor',
      email: 'jessica.taylor@example.com',
      phone: '+1 (555) 678-9012',
      type: 'Accommodation',
      item: 'Beachfront Cabin',
      startDate: new Date(2023, 7, 12),
      endDate: new Date(2023, 7, 19),
      status: 'Confirmed',
      amount: 1050,
      paymentStatus: 'Paid',
    },
    {
      id: 'B-5678',
      customer: 'Robert Martinez',
      email: 'robert.martinez@example.com',
      phone: '+1 (555) 789-0123',
      type: 'Boat',
      item: 'Party Catamaran',
      startDate: new Date(2023, 7, 15),
      endDate: new Date(2023, 7, 15),
      status: 'Pending',
      amount: 1200,
      paymentStatus: 'Unpaid',
    },
  ]

  const filteredBookings = selectedStatus === 'All' 
    ? bookings 
    : bookings.filter(booking => booking.status === selectedStatus)

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

  const getPaymentStatusBadgeClass = (status) => {
    switch (status) {
      case 'Paid':
        return 'badge-success'
      case 'Partial':
        return 'badge-warning'
      case 'Unpaid':
        return 'badge-danger'
      case 'Refunded':
        return 'badge-info'
      default:
        return 'badge-info'
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <h2 className="text-2xl font-bold text-gray-900">Bookings</h2>
        <div className="flex space-x-3">
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <FiSearch className="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
              placeholder="Search bookings..."
            />
          </div>
          
          <button
            className="btn btn-outline flex items-center"
            onClick={() => setFilterOpen(!filterOpen)}
          >
            <FiFilter className="mr-2 h-4 w-4" />
            Filter
          </button>
          
          <button className="btn btn-primary flex items-center">
            <FiPlus className="mr-2 h-4 w-4" />
            New Booking
          </button>
        </div>
      </div>
      
      {filterOpen && (
        <div className="card">
          <div className="flex flex-wrap gap-4">
            <div>
              <label className="label">Status</label>
              <select 
                className="input"
                value={selectedStatus}
                onChange={(e) => setSelectedStatus(e.target.value)}
              >
                <option>All</option>
                <option>Confirmed</option>
                <option>Pending</option>
                <option>Cancelled</option>
              </select>
            </div>
            
            <div>
              <label className="label">Type</label>
              <select className="input">
                <option>All</option>
                <option>Boat</option>
                <option>Accommodation</option>
              </select>
            </div>
            
            <div>
              <label className="label">Date Range</label>
              <div className="flex items-center space-x-2">
                <input type="date" className="input" />
                <span>to</span>
                <input type="date" className="input" />
              </div>
            </div>
            
            <div>
              <label className="label">Payment Status</label>
              <select className="input">
                <option>All</option>
                <option>Paid</option>
                <option>Partial</option>
                <option>Unpaid</option>
                <option>Refunded</option>
              </select>
            </div>
          </div>
          
          <div className="mt-4 flex justify-end space-x-3">
            <button className="btn btn-outline">Reset</button>
            <button className="btn btn-primary">Apply Filters</button>
          </div>
        </div>
      )}
      
      <div className="card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {filteredBookings.map((booking) => (
                <tr key={booking.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{booking.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="text-sm font-medium text-gray-900">{booking.customer}</div>
                    <div className="text-xs text-gray-500">{booking.email}</div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{booking.type}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{booking.item}</td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="text-sm text-gray-900">{format(booking.startDate, 'MMM d, yyyy')}</div>
                    {booking.startDate.getTime() !== booking.endDate.getTime() && (
                      <div className="text-xs text-gray-500">to {format(booking.endDate, 'MMM d, yyyy')}</div>
                    )}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`badge ${getStatusBadgeClass(booking.status)}`}>
                      {booking.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`badge ${getPaymentStatusBadgeClass(booking.paymentStatus)}`}>
                      {booking.paymentStatus}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${booking.amount}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div className="flex justify-end space-x-2">
                      <button className="text-primary hover:text-primary-dark" title="View">
                        <FiEye className="h-4 w-4" />
                      </button>
                      <button className="text-gray-600 hover:text-gray-900" title="Edit">
                        <FiEdit2 className="h-4 w-4" />
                      </button>
                      <button className="text-red-600 hover:text-red-900" title="Delete">
                        <FiTrash2 className="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div className="flex-1 flex justify-between sm:hidden">
            <button className="btn btn-outline">Previous</button>
            <button className="btn btn-outline">Next</button>
          </div>
          <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p className="text-sm text-gray-700">
                Showing <span className="font-medium">1</span> to <span className="font-medium">{filteredBookings.length}</span> of{' '}
                <span className="font-medium">{filteredBookings.length}</span> results
              </p>
            </div>
            <div>
              <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button className="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span className="sr-only">Previous</span>
                  <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fillRule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clipRule="evenodd" />
                  </svg>
                </button>
                <button className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                  1
                </button>
                <button className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                  2
                </button>
                <button className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                  3
                </button>
                <button className="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span className="sr-only">Next</span>
                  <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Bookings
