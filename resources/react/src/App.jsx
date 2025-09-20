import React, { useState, useEffect } from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { Toaster } from 'react-hot-toast';
import { Bell, Search, Book, Sparkles, Menu, X, User, LogOut } from 'lucide-react';
import BookSearchAdvanced from './components/BookSearchAdvanced';
import BookRecommendations from './components/BookRecommendations';
import NotificationCenter from './components/NotificationCenter';
import { userAPI, notificationsAPI } from './services/api';

// Create a client
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      staleTime: 5 * 60 * 1000, // 5 minutes
    },
  },
});

function App() {
  const [activeView, setActiveView] = useState('recommendations');
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [notificationOpen, setNotificationOpen] = useState(false);
  const [user, setUser] = useState(null);
  const [unreadCount, setUnreadCount] = useState(0);

  useEffect(() => {
    loadUser();
    loadNotificationStats();
  }, []);

  const loadUser = async () => {
    try {
      const response = await userAPI.getProfile();
      setUser(response.data);
    } catch (error) {
      console.error('User not authenticated');
      // User not logged in, that's okay for public features
    }
  };

  const loadNotificationStats = async () => {
    try {
      const response = await notificationsAPI.getStats();
      setUnreadCount(response.data.data.unread_notifications);
    } catch (error) {
      console.error('Failed to load notification stats');
    }
  };

  const navigation = [
    {
      id: 'recommendations',
      name: 'Rekomendasi',
      icon: Sparkles,
      description: 'Buku yang direkomendasikan untuk Anda'
    },
    {
      id: 'search',
      name: 'Pencarian Lanjutan',
      icon: Search,
      description: 'Cari buku dengan filter detail'
    },
  ];

  const renderContent = () => {
    switch (activeView) {
      case 'recommendations':
        return <BookRecommendations userId={user?.id} />;
      case 'search':
        return <BookSearchAdvanced />;
      default:
        return <BookRecommendations userId={user?.id} />;
    }
  };

  return (
    <QueryClientProvider client={queryClient}>
      <div className="min-h-screen bg-gray-50">
        {/* Mobile sidebar overlay */}
        {sidebarOpen && (
          <div className="fixed inset-0 z-40 lg:hidden">
            <div
              className="fixed inset-0 bg-gray-600 bg-opacity-75"
              onClick={() => setSidebarOpen(false)}
            />
          </div>
        )}

        {/* Sidebar */}
        <div className={`fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 ${
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        }`}>
          <div className="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <div className="flex items-center gap-2">
              <Book className="h-8 w-8 text-blue-600" />
              <span className="text-xl font-bold text-gray-900">
                Perpustakaan
              </span>
            </div>
            <button
              onClick={() => setSidebarOpen(false)}
              className="lg:hidden p-1 rounded-md hover:bg-gray-100"
            >
              <X className="h-5 w-5" />
            </button>
          </div>

          <nav className="mt-6 px-3">
            <div className="space-y-1">
              {navigation.map((item) => {
                const Icon = item.icon;
                const isActive = activeView === item.id;
                return (
                  <button
                    key={item.id}
                    onClick={() => {
                      setActiveView(item.id);
                      setSidebarOpen(false);
                    }}
                    className={`w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
                      isActive
                        ? 'bg-blue-100 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100'
                    }`}
                  >
                    <Icon className={`mr-3 h-5 w-5 ${
                      isActive ? 'text-blue-600' : 'text-gray-400'
                    }`} />
                    {item.name}
                  </button>
                );
              })}
            </div>

            {/* User Section */}
            {user && (
              <div className="mt-8 pt-6 border-t border-gray-200">
                <div className="px-3">
                  <div className="flex items-center">
                    <div className="bg-blue-100 p-2 rounded-full">
                      <User className="h-5 w-5 text-blue-600" />
                    </div>
                    <div className="ml-3">
                      <p className="text-sm font-medium text-gray-900">
                        {user.name}
                      </p>
                      <p className="text-xs text-gray-500 capitalize">
                        {user.role}
                      </p>
                    </div>
                  </div>
                  <button
                    onClick={() => {
                      localStorage.removeItem('auth_token');
                      window.location.href = '/login';
                    }}
                    className="mt-3 w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg"
                  >
                    <LogOut className="mr-3 h-4 w-4" />
                    Logout
                  </button>
                </div>
              </div>
            )}

            {/* Login Prompt for Guests */}
            {!user && (
              <div className="mt-8 pt-6 border-t border-gray-200">
                <div className="px-3">
                  <p className="text-sm text-gray-600 mb-3">
                    Login untuk fitur lengkap
                  </p>
                  <button
                    onClick={() => window.location.href = '/login'}
                    className="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                  >
                    Masuk
                  </button>
                </div>
              </div>
            )}
          </nav>
        </div>

        {/* Main content */}
        <div className="lg:pl-64">
          {/* Top bar */}
          <div className="sticky top-0 z-30 bg-white border-b border-gray-200">
            <div className="flex items-center justify-between h-16 px-6">
              <div className="flex items-center gap-4">
                <button
                  onClick={() => setSidebarOpen(true)}
                  className="lg:hidden p-2 rounded-md hover:bg-gray-100"
                >
                  <Menu className="h-5 w-5" />
                </button>

                <div>
                  <h1 className="text-lg font-semibold text-gray-900">
                    {navigation.find(item => item.id === activeView)?.name}
                  </h1>
                  <p className="text-sm text-gray-500">
                    {navigation.find(item => item.id === activeView)?.description}
                  </p>
                </div>
              </div>

              <div className="flex items-center gap-4">
                {/* Notifications */}
                {user && (
                  <button
                    onClick={() => setNotificationOpen(true)}
                    className="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg"
                  >
                    <Bell className="h-5 w-5" />
                    {unreadCount > 0 && (
                      <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {unreadCount > 9 ? '9+' : unreadCount}
                      </span>
                    )}
                  </button>
                )}

                {/* Quick Access to Main App */}
                <a
                  href="/dashboard"
                  className="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                  Dashboard Utama
                </a>
              </div>
            </div>
          </div>

          {/* Page content */}
          <main className="flex-1">
            {renderContent()}
          </main>
        </div>

        {/* Notification Center */}
        <NotificationCenter
          isOpen={notificationOpen}
          onClose={() => setNotificationOpen(false)}
        />

        {/* Toast notifications */}
        <Toaster
          position="top-right"
          toastOptions={{
            duration: 4000,
            style: {
              background: '#363636',
              color: '#fff',
            },
            success: {
              duration: 3000,
              iconTheme: {
                primary: '#10B981',
                secondary: '#fff',
              },
            },
            error: {
              duration: 5000,
              iconTheme: {
                primary: '#EF4444',
                secondary: '#fff',
              },
            },
          }}
        />
      </div>
    </QueryClientProvider>
  );
}

export default App;