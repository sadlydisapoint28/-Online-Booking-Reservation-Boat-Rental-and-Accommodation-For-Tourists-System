import React, { useState } from 'react'
import { FiPlus, FiEdit2, FiEye, FiTrash2, FiFilter, FiSearch } from 'react-icons/fi'

const Boats = () => {
  const [filterOpen, setFilterOpen] = useState(false)
  
  const boats = [
    {
      id: 1,
      name: 'Luxury Yacht',
      type: 'Yacht',
      capacity: 12,
      length: '45 ft',
      pricePerDay: 1500,
      status: 'Available',
      location: 'Marina Bay',
      image: 'https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 2,
      name: 'Sunset Cruiser',
      type: 'Motorboat',
      capacity: 8,
      length: '32 ft',
      pricePerDay: 350,
      status: 'Booked',
      location: 'Harbor Point',
      image: 'https://images.unsplash.com/photo-1569263979104-865ab7cd8d13?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 3,
      name: 'Fishing Boat',
      type: 'Fishing',
      capacity: 6,
      length: '28 ft',
      pricePerDay: 275,
      status: 'Maintenance',
      location: 'Fisherman\'s Wharf',
      image: 'https://images.unsplash.com/photo-1564132943780-67b66ef5a85e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 4,
      name: 'Party Catamaran',
      type: 'Catamaran',
      capacity: 20,
      length: '50 ft',
      pricePerDay: 1200,
      status: 'Available',
      location: 'Marina Bay',
      image: 'https://images.unsplash.com/photo-1540946485063-a40da27545f8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 5,
      name: 'Speed Boat',
      type: 'Speedboat',
      capacity: 4,
      length: '22 ft',
      pricePerDay: 200,
      status: 'Available',
      location: 'Harbor Point',
      image: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 6,
      name: 'Sailboat Adventure',
      type: 'Sailboat',
      capacity: 6,
      length: '35 ft',
      pricePerDay: 400,
      status: 'Booked',
      location: 'Sunset Marina',
      image: 'https://images.unsplash.com/photo-1534187886935-1e1236e856c3?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
  ]

  const getStatusBadgeClass = (status) => {
    switch (status) {
      case 'Available':
        return 'badge-success'
      case 'Booked':
        return 'badge-warning'
      case 'Maintenance':
        return 'badge-danger'
      default:
        return 'badge-info'
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <h2 className="text-2xl font-bold text-gray-900">Boats</h2>
        <div className="flex space-x-3">
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <FiSearch className="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
              placeholder="Search boats..."
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
            Add Boat
          </button>
        </div>
      </div>
      
      {filterOpen && (
        <div className="card">
          <div className="flex flex-wrap gap-4">
            <div>
              <label className="label">Boat Type</label>
              <select className="input">
                <option>All Types</option>
                <option>Yacht</option>
                <option>Motorboat</option>
                <option>Fishing</option>
                <option>Catamaran</option>
                <option>Speedboat</option>
                <option>Sailboat</option>
              </select>
            </div>
            
            <div>
              <label className="label">Status</label>
              <select className="input">
                <option>All</option>
                <option>Available</option>
                <option>Booked</option>
                <option>Maintenance</option>
              </select>
            </div>
            
            <div>
              <label className="label">Location</label>
              <select className="input">
                <option>All Locations</option>
                <option>Marina Bay</option>
                <option>Harbor Point</option>
                <option>Fisherman's Wharf</option>
                <option>Sunset Marina</option>
              </select>
            </div>
            
            <div>
              <label className="label">Capacity</label>
              <div className="flex items-center space-x-2">
                <input type="number" className="input" placeholder="Min" />
                <span>to</span>
                <input type="number" className="input" placeholder="Max" />
              </div>
            </div>
          </div>
          
          <div className="mt-4 flex justify-end space-x-3">
            <button className="btn btn-outline">Reset</button>
            <button className="btn btn-primary">Apply Filters</button>
          </div>
        </div>
      )}
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {boats.map((boat) => (
          <div key={boat.id} className="card overflow-hidden flex flex-col">
            <div className="h-48 w-full overflow-hidden">
              <img 
                src={boat.image} 
                alt={boat.name} 
                className="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
              />
            </div>
            
            <div className="p-4 flex-1 flex flex-col">
              <div className="flex justify-between items-start">
                <h3 className="text-lg font-semibold text-gray-900">{boat.name}</h3>
                <span className={`badge ${getStatusBadgeClass(boat.status)}`}>
                  {boat.status}
                </span>
              </div>
              
              <p className="text-sm text-gray-500 mt-1">{boat.type}</p>
              
              <div className="mt-4 grid grid-cols-2 gap-2 text-sm">
                <div>
                  <p className="text-gray-500">Capacity</p>
                  <p className="font-medium">{boat.capacity} people</p>
                </div>
                <div>
                  <p className="text-gray-500">Length</p>
                  <p className="font-medium">{boat.length}</p>
                </div>
                <div>
                  <p className="text-gray-500">Location</p>
                  <p className="font-medium">{boat.location}</p>
                </div>
                <div>
                  <p className="text-gray-500">Price</p>
                  <p className="font-medium">${boat.pricePerDay}/day</p>
                </div>
              </div>
              
              <div className="mt-auto pt-4 flex justify-between">
                <button className="btn btn-outline flex items-center text-xs">
                  <FiEye className="mr-1 h-3 w-3" />
                  View Details
                </button>
                <div className="flex space-x-2">
                  <button className="p-2 text-gray-600 hover:text-gray-900" title="Edit">
                    <FiEdit2 className="h-4 w-4" />
                  </button>
                  <button className="p-2 text-red-600 hover:text-red-900" title="Delete">
                    <FiTrash2 className="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default Boats
