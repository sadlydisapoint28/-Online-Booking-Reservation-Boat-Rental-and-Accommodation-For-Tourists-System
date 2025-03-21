import React, { useState } from 'react'
import { useLocation } from 'react-router-dom'
import { 
  FiMenu, 
  FiBell, 
  FiSearch, 
  FiHelpCircle, 
  FiMessageSquare 
} from 'react-icons/fi'

const Header = ({ sidebarOpen, setSidebarOpen }) => {
  const [notificationsOpen, setNotificationsOpen] = useState(false)
  const location = useLocation()
  
  // Get page title based on current route
  const getPageTitle = () => {
    const path = location.pathname
    if (path === '/') return 'Dashboard'
    return path.charAt(1).toUpperCase() + path.slice(2)
  }

  const notifications = [
    { id: 1, message: 'New booking request for Sunset Cruiser', time: '5 minutes ago', unread: true },
    { id: 2, message: 'Booking #1234 has been confirmed', time: '1 hour ago', unread: true },
    { id: 3, message: 'Customer feedback received for Beach Villa', time: '3 hours ago', unread: false },
    { id: 4, message: 'Maintenance scheduled for Luxury Yacht', time: 'Yesterday', unread: false },
  ]

  return (
    <header className="bg-white shadow-sm z-10">
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center">
            <button
              type="button"
              className="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
              onClick={() => setSidebarOpen(true)}
              data-tutorial="menu-toggle"
            >
              <FiMenu className="h-6 w-6" />
            </button>
            <h1 className="ml-2 md:ml-0 text-xl font-semibold text-gray-800">{getPageTitle()}</h1>
          </div>
          
          <div className="hidden md:block">
            <div className="relative">
              <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <FiSearch className="h-5 w-5 text-gray-400" />
              </div>
              <input
                type="text"
                className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
                placeholder="Search..."
                data-tutorial="search"
              />
            </div>
          </div>
          
          <div className="flex items-center space-x-4">
            <button 
              className="text-gray-500 hover:text-gray-700 focus:outline-none"
              data-tutorial="help"
            >
              <FiHelpCircle className="h-6 w-6" />
            </button>
            
            <button 
              className="text-gray-500 hover:text-gray-700 focus:outline-none"
              data-tutorial="messages"
            >
              <FiMessageSquare className="h-6 w-6" />
            </button>
            
            <div className="relative">
              <button 
                className="text-gray-500 hover:text-gray-700 focus:outline-none"
                onClick={() => setNotificationsOpen(!notificationsOpen)}
                data-tutorial="notifications"
              >
                <FiBell className="h-6 w-6" />
                <span className="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
              </button>
              
              {notificationsOpen && (
                <div className="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                  <div className="py-2 px-4 border-b border-gray-200">
                    <h3 className="text-sm font-medium text-gray-900">Notifications</h3>
                  </div>
                  <div className="max-h-80 overflow-y-auto">
                    {notifications.map((notification) => (
                      <div 
                        key={notification.id} 
                        className={`px-4 py-3 hover:bg-gray-50 ${notification.unread ? 'bg-blue-50' : ''}`}
                      >
                        <p className="text-sm text-gray-800">{notification.message}</p>
                        <p className="text-xs text-gray-500 mt-1">{notification.time}</p>
                      </div>
                    ))}
                  </div>
                  <div className="py-2 px-4 border-t border-gray-200 text-center">
                    <button className="text-sm text-primary hover:text-primary-dark">
                      View all notifications
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </header>
  )
}

export default Header
