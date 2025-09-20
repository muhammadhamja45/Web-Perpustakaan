import React, { useState, useEffect } from 'react';
import { Sparkles, TrendingUp, Clock, Star, BookOpen, ChevronRight } from 'lucide-react';
import { recommendationsAPI } from '../services/api';
import { toast } from 'react-hot-toast';
import BookCard from './BookCard';
import LoadingSpinner from './LoadingSpinner';

const BookRecommendations = ({ userId }) => {
  const [recommendations, setRecommendations] = useState({
    popular: [],
    trending: [],
    personal: [],
    newArrivals: []
  });
  const [loading, setLoading] = useState({
    popular: false,
    trending: false,
    personal: false,
    newArrivals: false
  });
  const [activeTab, setActiveTab] = useState('personal');
  const [userProfile, setUserProfile] = useState(null);

  useEffect(() => {
    loadRecommendations();
  }, [userId]);

  const loadRecommendations = async () => {
    // Load all recommendation types
    await Promise.all([
      loadPopularBooks(),
      loadTrendingBooks(),
      loadPersonalRecommendations(),
      loadNewArrivals()
    ]);
  };

  const loadPopularBooks = async () => {
    setLoading(prev => ({ ...prev, popular: true }));
    try {
      const response = await recommendationsAPI.getPopular({ limit: 8 });
      setRecommendations(prev => ({
        ...prev,
        popular: response.data.data
      }));
    } catch (error) {
      console.error('Failed to load popular books:', error);
    } finally {
      setLoading(prev => ({ ...prev, popular: false }));
    }
  };

  const loadTrendingBooks = async () => {
    setLoading(prev => ({ ...prev, trending: true }));
    try {
      const response = await recommendationsAPI.getTrending({ limit: 8 });
      setRecommendations(prev => ({
        ...prev,
        trending: response.data.data
      }));
    } catch (error) {
      console.error('Failed to load trending books:', error);
    } finally {
      setLoading(prev => ({ ...prev, trending: false }));
    }
  };

  const loadPersonalRecommendations = async () => {
    if (!userId) return;

    setLoading(prev => ({ ...prev, personal: true }));
    try {
      const response = await recommendationsAPI.getPersonal({ limit: 8 });
      setRecommendations(prev => ({
        ...prev,
        personal: response.data.data
      }));
      setUserProfile(response.data.user_profile);
    } catch (error) {
      console.error('Failed to load personal recommendations:', error);
      // Fallback to popular books if personal recommendations fail
      if (error.response?.status === 401) {
        setActiveTab('popular');
      }
    } finally {
      setLoading(prev => ({ ...prev, personal: false }));
    }
  };

  const loadNewArrivals = async () => {
    setLoading(prev => ({ ...prev, newArrivals: true }));
    try {
      const response = await recommendationsAPI.getNewArrivals({ limit: 8 });
      setRecommendations(prev => ({
        ...prev,
        newArrivals: response.data.data
      }));
    } catch (error) {
      console.error('Failed to load new arrivals:', error);
    } finally {
      setLoading(prev => ({ ...prev, newArrivals: false }));
    }
  };

  const tabs = [
    {
      id: 'personal',
      label: 'Untuk Anda',
      icon: Sparkles,
      description: 'Rekomendasi berdasarkan riwayat baca Anda',
      requiresAuth: true
    },
    {
      id: 'popular',
      label: 'Populer',
      icon: Star,
      description: 'Buku paling banyak dipinjam'
    },
    {
      id: 'trending',
      label: 'Trending',
      icon: TrendingUp,
      description: 'Buku yang sedang naik daun'
    },
    {
      id: 'newArrivals',
      label: 'Baru',
      icon: Clock,
      description: 'Koleksi terbaru perpustakaan'
    }
  ];

  const currentRecommendations = recommendations[activeTab] || [];
  const isLoading = loading[activeTab];

  return (
    <div className="max-w-7xl mx-auto p-6">
      {/* Header */}
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-2">
          Rekomendasi Buku
        </h1>
        <p className="text-gray-600">
          Temukan buku-buku menarik yang sesuai dengan minat Anda
        </p>
      </div>

      {/* User Profile (for personal recommendations) */}
      {activeTab === 'personal' && userProfile && (
        <div className="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-8">
          <div className="flex items-center gap-4">
            <div className="bg-white p-3 rounded-full">
              <BookOpen className="h-6 w-6 text-blue-600" />
            </div>
            <div>
              <h3 className="text-lg font-semibold text-gray-900">
                Profil Bacaan Anda
              </h3>
              <div className="text-sm text-gray-600 mt-1">
                <span className="inline-block mr-4">
                  📚 {userProfile.books_borrowed} buku dipinjam
                </span>
                {userProfile.favorite_authors?.length > 0 && (
                  <span className="inline-block mr-4">
                    ✍️ Penulis favorit: {userProfile.favorite_authors.slice(0, 2).join(', ')}
                  </span>
                )}
                {userProfile.favorite_categories?.length > 0 && (
                  <span className="inline-block">
                    📖 Kategori favorit: {userProfile.favorite_categories.slice(0, 2).join(', ')}
                  </span>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Tabs */}
      <div className="border-b border-gray-200 mb-8">
        <nav className="-mb-px flex space-x-8 overflow-x-auto">
          {tabs.map((tab) => {
            const Icon = tab.icon;
            const isActive = activeTab === tab.id;
            const isDisabled = tab.requiresAuth && !userId;

            return (
              <button
                key={tab.id}
                onClick={() => !isDisabled && setActiveTab(tab.id)}
                disabled={isDisabled}
                className={`group inline-flex items-center py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap ${
                  isActive
                    ? 'border-blue-500 text-blue-600'
                    : isDisabled
                    ? 'border-transparent text-gray-400 cursor-not-allowed'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                }`}
              >
                <Icon className={`mr-2 h-4 w-4 ${
                  isActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'
                }`} />
                {tab.label}
              </button>
            );
          })}
        </nav>
      </div>

      {/* Tab Description */}
      <div className="mb-6">
        <p className="text-gray-600">
          {tabs.find(tab => tab.id === activeTab)?.description}
        </p>
      </div>

      {/* Content */}
      {isLoading ? (
        <div className="flex justify-center items-center py-12">
          <LoadingSpinner />
        </div>
      ) : currentRecommendations.length > 0 ? (
        <div>
          {/* Books Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {currentRecommendations.map((book) => (
              <div key={book.id} className="relative">
                <BookCard book={book} />

                {/* Recommendation Reason */}
                {book.recommendation_reason && (
                  <div className="mt-2 text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                    💡 {book.recommendation_reason}
                  </div>
                )}

                {/* Special Badges */}
                {book.growth_rate > 0 && (
                  <div className="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                    📈 +{book.growth_rate}%
                  </div>
                )}

                {book.days_since_added <= 7 && (
                  <div className="absolute top-2 right-2 bg-purple-500 text-white text-xs px-2 py-1 rounded-full">
                    🆕 Baru
                  </div>
                )}
              </div>
            ))}
          </div>

          {/* Load More Button */}
          <div className="text-center mt-8">
            <button
              onClick={() => {
                // Load more recommendations
                switch (activeTab) {
                  case 'popular':
                    loadPopularBooks();
                    break;
                  case 'trending':
                    loadTrendingBooks();
                    break;
                  case 'personal':
                    loadPersonalRecommendations();
                    break;
                  case 'newArrivals':
                    loadNewArrivals();
                    break;
                }
              }}
              className="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50"
            >
              Muat Lebih Banyak
              <ChevronRight className="ml-2 h-4 w-4" />
            </button>
          </div>
        </div>
      ) : (
        <div className="text-center py-12">
          <BookOpen className="h-16 w-16 text-gray-300 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">
            {activeTab === 'personal' && !userId
              ? 'Login untuk Rekomendasi Personal'
              : 'Belum Ada Rekomendasi'
            }
          </h3>
          <p className="text-gray-500 max-w-md mx-auto">
            {activeTab === 'personal' && !userId
              ? 'Masuk ke akun Anda untuk mendapatkan rekomendasi buku yang dipersonalisasi berdasarkan riwayat baca.'
              : 'Rekomendasi akan muncul setelah ada aktivitas peminjaman buku.'
            }
          </p>
          {activeTab === 'personal' && !userId && (
            <button
              onClick={() => window.location.href = '/login'}
              className="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Masuk Sekarang
            </button>
          )}
        </div>
      )}

      {/* Similar Books Section (when viewing a specific book) */}
      {/* This would be rendered in a book detail page */}
    </div>
  );
};

export default BookRecommendations;