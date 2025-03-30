import React, { useState } from 'react'
import { FiPlus, FiEdit2, FiEye, FiTrash2, FiFilter, FiSearch } from 'react-icons/fi'

const Accommodations = () => {
  const [filterOpen, setFilterOpen] = useState(false)
  
  const accommodations = [
    {
      id: 1,
      name: 'Beach Villa',
      type: 'Villa',
      bedrooms: 3,
      bathrooms: 2,
      capacity: 6,
      pricePerNight: 350,
      status: 'Available',
      location: 'Beachfront',
      amenities: ['Pool', 'Wi-Fi', 'Kitchen', 'Air Conditioning', 'Ocean View'],
      image: 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 2,
      name: 'Ocean View Suite',
      type: 'Suite',
      bedrooms: 1,
      bathrooms: 1,
      capacity: 2,
      pricePerNight: 180,
      status: 'Booked',
      location: 'Resort Area',
      amenities: ['Wi-Fi', 'Air Conditioning', 'Ocean View', 'Room Service'],
      image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 3,
      name: 'Beachfront Cabin',
      type: 'Cabin',
      bedrooms: 2,
      bathrooms: 1,
      capacity: 4,
      pricePerNight: 220,
      status: 'Maintenance',
      location: 'Beachfront',
      amenities: ['Wi-Fi', 'Kitchen', 'Beach Access', 'Parking'],
      image: 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 4,
      name: 'Luxury Villa',
      type: 'Villa',
      bedrooms: 4,
      bathrooms: 3,
      capacity: 8,
      pricePerNight: 450,
      status: 'Available',
      location: 'Hillside',
      amenities: ['Pool', 'Wi-Fi', 'Kitchen', 'Air Conditioning', 'Mountain View', 'Jacuzzi'],
      image: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 5,
      name: 'Cozy Cottage',
      type: 'Cottage',
      bedrooms: 1,
      bathrooms: 1,
      capacity: 2,
      pricePerNight: 150,
      status: 'Available',
      location: 'Garden Area',
      amenities: ['Wi-Fi', 'Kitchen', 'Garden View', 'Parking'],
      image: 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
    },
    {
      id: 6,
      name: 'Family Bungalow',
      type: 'Bungalow',
      bedrooms: 3,
      bathrooms: 2,
      capacity: 6,
      pricePerNight: 280,
      status: 'Booked',
      location: 'Resort Area',
      amenities: ['Pool', 'Wi-Fi', 'Kitchen', 'Air Conditioning', 'Garden View'],
      image: 'https://images.unsplash.com/photo-1540541338287-41700207dee6?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
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
        <h2 className="text-2xl font-bold text-gray-900">Accommodations</h2>
        <div className="flex space-x-3">
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <FiSearch className="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
              placeholder="Search accommodations..."
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
            Add Accommodation
          </button>
        </div>
      </div>
      
      {filterOpen && (
        <div className="card">
          <div className="flex flex-wrap gap-4">
            <div>
              <label className="label">Type</label>
              <select className="input">
                <option>All Types</option>
                <option>Villa</option>
                <option>Suite</option>
                <option>Cabin</option>
                <option>Cottage</option>
                <option>Bungalow</option>
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
                <option>Beachfront</option>
                <option>Resort Area</option>
                <option>Hillside</option>
                <option>Garden Area</option>
              </select>
            </div>
            
            <div>
              <label className="label">Bedrooms</label>
              <div className="flex items-center space-x-2">
                <input type="number" className="input" placeholder="Min" />
                <span>to</span>
                <input type="number" className="input" placeholder="Max" />
              </div>
            </div>
            
            <div>
              <label className="label">Price per Night</label>
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
        {accommodations.map((accommodation) => (
          <div key={accommodation.id} className="card overflow-hidden flex flex-col">
            <div className="h-48 w-full overflow-hidden">
              <img 
                src={accommodation.image} 
                alt={accommodation.name} 
                className="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
              />
            </div>
            
            <div className="p-4 flex-1 flex flex-col">
              <div className="flex justify-between items-start">
                <h3 className="text-lg font-semibold text-gray-900">{accommodation.name}</h3>
                <span className={`badge ${getStatusBadgeClass(accommodation.status)}`}>
                  {accommodation.status}
                </span>
              </div>
              
              <p className="text-sm text-gray-500 mt-1">{accommodation.type} • {accommodation.location}</p>
              
              <div className="mt-4 grid grid-cols-3 gap-2 text-sm">
                <div>
                  <p className="text-gray-500">Bedrooms</p>
                  <p className="font-medium">{accommodation.bedrooms}</p>
                </div>
                <div>
                  <p className="text-gray-500">Bathrooms</p>
                  <p className="font-medium">{accommodation.bathrooms}</p>
                </div>
                <div>
                  <p className="text-gray-500">Capacity</p>
                  <p className="font-medium">{accommodation.capacity} guests</p>
                </div>
              </div>
              
              <div className="mt-3">
                <p className="text-sm text-gray-500">Amenities</p>
                <div className="mt-1 flex flex-wrap gap-1">
                  {accommodation.amenities.slice(0, 3).map((amenity, index) => (
                    <span key={index} className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                      {amenity}
                    </span>
                  ))}
                  {accommodation.amenities.length > 3 && (
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                      +{accommodation.amenities.length - 3} more
                    </span>
                  )}
                </div>
              </div>
              
              <div className="mt-4 flex items-center justify-between">
                <div>
                  <p className="text-gray-500 text-sm">Price per night</p>
                  <p className="text-lg font-semibold text-gray-900">${accommodation.pricePerNight}</p>
                </div>
                
                <div className="flex space-x-2">
                  <button className="btn btn-outline flex items-center text-xs py-1">
                    <FiEye className="mr-1 h-3 w-3" />
                    View
                  </button>
                  <button className="p-1 text-gray-600 hover:text-gray-900" title="Edit">
                    <FiEdit2 className="h-4 w-4" />
                  </button>
                  <button className="p-1 text-red-600 hover:text-red-900" title="Delete">
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

export default Accommodations
