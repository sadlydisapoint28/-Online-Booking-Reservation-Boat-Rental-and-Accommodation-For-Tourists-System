import React, { useState } from 'react'
import { FiX, FiArrowRight, FiArrowLeft, FiCheckCircle } from 'react-icons/fi'

const TutorialOverlay = ({ onComplete }) => {
  const [currentStep, setCurrentStep] = useState(0)
  
  const tutorialSteps = [
    {
      title: "Welcome to Coastal Getaways Admin Dashboard!",
      content: "This quick tutorial will guide you through the main features of your new booking management system. Let's get started!",
      position: "center",
    },
    {
      title: "Navigation Menu",
      content: "Use the sidebar to navigate between different sections of the dashboard. You can manage bookings, boats, accommodations, customers, and more.",
      position: "left",
      target: "[data-tutorial='dashboard']",
    },
    {
      title: "Bookings Management",
      content: "View and manage all your reservations in one place. You can filter, sort, and process booking requests efficiently.",
      position: "left",
      target: "[data-tutorial='bookings']",
    },
    {
      title: "Boats Inventory",
      content: "Manage your fleet of boats, update availability, pricing, and maintenance schedules.",
      position: "left",
      target: "[data-tutorial='boats']",
    },
    {
      title: "Accommodations",
      content: "Keep track of all your properties, from beachfront villas to cozy cabins.",
      position: "left",
      target: "[data-tutorial='accommodations']",
    },
    {
      title: "Customer Database",
      content: "Access customer information, booking history, and preferences to provide personalized service.",
      position: "left",
      target: "[data-tutorial='customers']",
    },
    {
      title: "Reports & Analytics",
      content: "Get insights into your business performance with detailed reports and visualizations.",
      position: "left",
      target: "[data-tutorial='reports']",
    },
    {
      title: "Search Functionality",
      content: "Quickly find what you need using the search bar at the top of the page.",
      position: "top",
      target: "[data-tutorial='search']",
    },
    {
      title: "Notifications",
      content: "Stay updated with booking requests, confirmations, and other important alerts.",
      position: "bottom",
      target: "[data-tutorial='notifications']",
    },
    {
      title: "You're All Set!",
      content: "You now know the basics of your new admin dashboard. Feel free to explore and discover more features!",
      position: "center",
    },
  ]

  const handleNext = () => {
    if (currentStep < tutorialSteps.length - 1) {
      setCurrentStep(currentStep + 1)
    } else {
      onComplete()
    }
  }

  const handlePrevious = () => {
    if (currentStep > 0) {
      setCurrentStep(currentStep - 1)
    }
  }

  const handleSkip = () => {
    onComplete()
  }

  const currentTutorial = tutorialSteps[currentStep]
  
  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
      <div className="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div className="flex justify-between items-center mb-4">
          <h3 className="text-lg font-medium text-gray-900">
            {currentTutorial.title}
          </h3>
          <button
            onClick={handleSkip}
            className="text-gray-400 hover:text-gray-500"
          >
            <FiX className="h-5 w-5" />
          </button>
        </div>
        
        <div className="mb-6">
          <p className="text-gray-600">{currentTutorial.content}</p>
        </div>
        
        <div className="flex justify-between items-center">
          <div>
            {currentStep > 0 && (
              <button
                onClick={handlePrevious}
                className="flex items-center text-sm text-gray-600 hover:text-gray-900"
              >
                <FiArrowLeft className="mr-1 h-4 w-4" />
                Previous
              </button>
            )}
          </div>
          
          <div className="flex space-x-2">
            <button
              onClick={handleSkip}
              className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
            >
              Skip Tutorial
            </button>
            
            <button
              onClick={handleNext}
              className="flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark"
            >
              {currentStep < tutorialSteps.length - 1 ? (
                <>
                  Next
                  <FiArrowRight className="ml-1 h-4 w-4" />
                </>
              ) : (
                <>
                  Finish
                  <FiCheckCircle className="ml-1 h-4 w-4" />
                </>
              )}
            </button>
          </div>
        </div>
        
        <div className="mt-4 flex justify-center">
          <div className="flex space-x-1">
            {tutorialSteps.map((_, index) => (
              <div
                key={index}
                className={`h-1.5 w-6 rounded-full ${
                  index === currentStep ? 'bg-primary' : 'bg-gray-200'
                }`}
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}

export default TutorialOverlay
