import React from 'react'
import { NavLink } from 'react-router-dom'
import { 
  FiHome, 
  FiCalendar, 
  FiAnchor, 
  FiHome as FiHouse, 
  FiUsers, 
  FiBarChart2, 
  FiSettings, 
  FiX 
} from 'react-icons/fi'

const Sidebar = ({ open, setOpen }) => {
  const navItems = [
    { name: 'Dashboard', path: '/', icon: <FiHome className="w-5 h-5" /> },
    { name: 'Bookings', path: '/bookings', icon: <FiCalendar className="w-5 h-5" /> },
    { name: 'Boats', path: '/boats', icon: <FiAnchor className="w-5 h-5" /> },
    { name: 'Accommodations', path: '/accommodations', icon: <FiHouse className="w-5 h-5" /> },
    { name: 'Customers', path: '/customers', icon: <FiUsers className="w-5 h-5" /> },
    { name: 'Reports', path: '/reports', icon: <FiBarChart2 className="w-5 h-5" /> },
    { name: 'Settings', path: '/settings', icon: <FiSettings className="w-5 h-5" /> },
  ]

  return (
    <>
      {/* Mobile sidebar backdrop */}
      <div 
        className={`fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden ${
          open ? 'opacity-100' : 'opacity-0 pointer-events-none'
        }`}
        onClick={() => setOpen(false)}
      />

      {/* Sidebar */}
      <aside
        className={`fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:h-screen ${
          open ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="h-full flex flex-col">
          {/* Sidebar header */}
          <div className="flex items-center justify-between px-4 py-5 border-b">
            <div className="flex items-center">
              <div className="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                <FiAnchor className="text-white w-6 h-6" />
              </div>
              <h1 className="ml-3 text-xl font-bold text-gray-800">Coastal Getaways</h1>
            </div>
            <button 
              className="md:hidden text-gray-500 hover:text-gray-700"
              onClick={() => setOpen(false)}
            >
              <FiX className="w-6 h-6" />
            </button>
          </div>

          {/* Navigation */}
          <nav className="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            {navItems.map((item) => (
              <NavLink
                key={item.name}
                to={item.path}
                className={({ isActive }) => 
                  `flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors ${
                    isActive 
                      ? 'bg-primary text-white' 
                      : 'text-gray-700 hover:bg-gray-100'
                  }`
                }
                data-tutorial={item.name.toLowerCase()}
              >
                {item.icon}
                <span className="ml-3">{item.name}</span>
              </NavLink>
            ))}
          </nav>

          {/* User profile */}
          <div className="p-4 border-t">
            <div className="flex items-center">
              <img
                className="w-10 h-10 rounded-full"
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                alt="User avatar"
              />
              <div className="ml-3">
                <p className="text-sm font-medium text-gray-800">Admin User</p>
                <p className="text-xs text-gray-500">admin@coastalgetaways.com</p>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </>
  )
}

export default Sidebar
