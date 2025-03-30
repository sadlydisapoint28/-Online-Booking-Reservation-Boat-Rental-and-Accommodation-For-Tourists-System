import React from 'react'
import { FiAnchor, FiHome } from 'react-icons/fi'

const PopularItems = () => {
  const popularItems = [
    {
      id: 1,
      name: 'Luxury Yacht',
      type: 'boat',
      bookings: 28,
      rating: 4.9,
      revenue: 42000,
    },
    {
      id: 2,
      name: 'Beach Villa',
      type: 'accommodation',
      bookings: 24,
      rating: 4.8,
      revenue: 38400,
    },
    {
      id: 3,
      name: 'Sunset Cruiser',
      type: 'boat',
      bookings: 22,
      rating: 4.7,
      revenue: 33000,
    },
    {
      id: 4,
      name: 'Ocean View Suite',
      type: 'accommodation',
      bookings: 20,
      rating: 4.6,
      revenue: 30000,
    },
    {
      id: 5,
      name: 'Party Catamaran',
      type: 'boat',
      bookings: 18,
      rating: 4.5,
      revenue: 27000,
    },
  ]

  return (
    <div className="card h-full">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-medium text-gray-900">Popular Items</h3>
        <div className="flex space-x-2">
          <button className="text-xs px-2 py-1 rounded bg-primary text-white">
            This Month
          </button>
          <button className="text-xs px-2 py-1 rounded text-gray-700 hover:bg-gray-100">
            Last Month
          </button>
        </div>
      </div>
      
      <div className="space-y-4">
        {popularItems.map((item) => (
          <div key={item.id} className="flex items-center p-3 border rounded-lg hover:bg-gray-50">
            <div className={`p-3 rounded-full ${
              item.type === 'boat' ? 'bg-primary-light' : 'bg-secondary-light'
            }`}>
              {item.type === 'boat' ? (
                <FiAnchor className="h-5 w-5 text-white" />
              ) : (
                <FiHome className="h-5 w-5 text-white" />
              )}
            </div>
            
            <div className="ml-4 flex-1">
              <div className="flex items-center justify-between">
                <h4 className="text-sm font-medium text-gray-900">{item.name}</h4>
                <span className="text-xs font-medium text-gray-500">
                  {item.bookings} bookings
                </span>
              </div>
              
              <div className="flex items-center justify-between mt-1">
                <div className="flex items-center">
                  <div className="flex">
                    {[...Array(5)].map((_, i) => (
                      <svg 
                        key={i} 
                        className={`h-3 w-3 ${i < Math.floor(item.rating) ? 'text-yellow-400' : 'text-gray-300'}`} 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                  <span className="ml-1 text-xs text-gray-500">{item.rating}</span>
                </div>
                <span className="text-xs font-medium text-gray-700">
                  ${item.revenue.toLocaleString()}
                </span>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default PopularItems
