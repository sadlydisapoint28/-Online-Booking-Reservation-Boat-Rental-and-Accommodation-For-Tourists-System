import React, { useState, useEffect } from 'react'
import { Routes, Route, useLocation } from 'react-router-dom'
import Sidebar from './components/layout/Sidebar'
import Header from './components/layout/Header'
import Dashboard from './pages/Dashboard'
import Bookings from './pages/Bookings'
import Boats from './pages/Boats'
import Accommodations from './pages/Accommodations'
import Customers from './pages/Customers'
import Reports from './pages/Reports'
import Settings from './pages/Settings'
import TutorialOverlay from './components/TutorialOverlay'

function App() {
  const [sidebarOpen, setSidebarOpen] = useState(true)
  const [showTutorial, setShowTutorial] = useState(false)
  const location = useLocation()

  // Check if it's the first visit to show tutorial
  useEffect(() => {
    const hasSeenTutorial = localStorage.getItem('hasSeenTutorial')
    if (!hasSeenTutorial) {
      setShowTutorial(true)
    }
  }, [])

  const completeTutorial = () => {
    setShowTutorial(false)
    localStorage.setItem('hasSeenTutorial', 'true')
  }

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar open={sidebarOpen} setOpen={setSidebarOpen} />
      
      <div className="flex-1 flex flex-col overflow-hidden">
        <Header sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />
        
        <main className="flex-1 overflow-y-auto p-4 md:p-6">
          <Routes>
            <Route path="/" element={<Dashboard />} />
            <Route path="/bookings" element={<Bookings />} />
            <Route path="/boats" element={<Boats />} />
            <Route path="/accommodations" element={<Accommodations />} />
            <Route path="/customers" element={<Customers />} />
            <Route path="/reports" element={<Reports />} />
            <Route path="/settings" element={<Settings />} />
          </Routes>
        </main>
      </div>

      {showTutorial && <TutorialOverlay onComplete={completeTutorial} />}
    </div>
  )
}

export default App
