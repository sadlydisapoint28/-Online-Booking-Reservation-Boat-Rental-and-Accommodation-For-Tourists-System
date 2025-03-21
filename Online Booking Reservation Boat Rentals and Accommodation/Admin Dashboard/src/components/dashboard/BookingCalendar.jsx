import React from 'react'
import { format, startOfMonth, endOfMonth, eachDayOfInterval, isSameMonth, isToday, isSameDay } from 'date-fns'

const BookingCalendar = () => {
  const today = new Date()
  const firstDay = startOfMonth(today)
  const lastDay = endOfMonth(today)
  const days = eachDayOfInterval({ start: firstDay, end: lastDay })
  
  // Sample booking data
  const bookings = [
    { id: 1, date: new Date(2023, today.getMonth(), 5), type: 'boat', name: 'Sunset Cruiser' },
    { id: 2, date: new Date(2023, today.getMonth(), 5), type: 'accommodation', name: 'Beach Villa' },
    { id: 3, date: newDate(2023, today.getMonth(), 8), type: 'boat', name: 'Luxury Yacht' },
    { id: 4, date: new Date(2023, today.getMonth(), 12), type: 'accommodation', name: 'Ocean View Suite' },
    { id: 5, date: new Date(2023, today.getMonth(), 15), type: 'boat', name: 'Fishing Boat' },
    { id: 6, date: new Date(2023, today.getMonth(), 18), type: 'accommodation', name: 'Beachfront Cabin' },
    { id: 7, date: new Date(2023, today.getMonth(), 22), type: 'boat', name: 'Party Catamaran' },
    { id: 8, date: new Date(2023, today.getMonth(), 25), type: 'accommodation', name: 'Luxury Villa' },
  ]

  const getBookingsForDay = (day) => {
    return bookings.filter(booking => isSameDay(booking.date, day))
  }

  return (
    <div className="card h-full">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-medium text-gray-900">Booking Calendar</h3>
        <p className="text-sm font-medium text-gray-500">{format(today, 'MMMM yyyy')}</p>
      </div>
      
      <div className="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
        {['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map((day) => (
          <div key={day}>{day}</div>
        ))}
      </div>
      
      <div className="grid grid-cols-7 gap-1">
        {days.map((day) => {
          const dayBookings = getBookingsForDay(day)
          
          return (
            <div 
              key={day.toString()} 
              className={`min-h-[80px] p-1 border rounded-md ${
                isToday(day) 
                  ? 'bg-blue-50 border-blue-200' 
                  : 'border-gray-200'
              }`}
            >
              <div className="text-right text-xs font-medium">
                {format(day, 'd')}
              </div>
              
              <div className="mt-1 space-y-1 overflow-y-auto max-h-[60px]">
                {dayBookings.map((booking) => (
                  <div 
                    key={booking.id}
                    className={`text-xs px-1 py-0.5 rounded truncate ${
                      booking.type === 'boat' 
                        ? 'bg-primary-light text-white' 
                        : 'bg-secondary-light text-white'
                    }`}
                    title={booking.name}
                  >
                    {booking.name}
                  </div>
                ))}
              </div>
            </div>
          )
        })}
      </div>
    </div>
  )
}

export default BookingCalendar
