import React, { useState } from 'react';
import { BookOpen, User, Calendar, Hash, Heart, Eye, Download } from 'lucide-react';
import { loansAPI } from '../services/api';
import { toast } from 'react-hot-toast';

const BookCard = ({ book, onBorrow, showActions = true }) => {
  const [borrowing, setBorrowing] = useState(false);
  const [liked, setLiked] = useState(false);

  const handleBorrow = async () => {
    setBorrowing(true);
    try {
      await loansAPI.create(book.id);
      toast.success(`Berhasil meminjam "${book.title}"`);
      if (onBorrow) {
        onBorrow(book);
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Gagal meminjam buku';
      toast.error(errorMessage);
    } finally {
      setBorrowing(false);
    }
  };

  const handleLike = () => {
    setLiked(!liked);
    // In a real app, this would call an API to save/remove from favorites
    toast.success(liked ? 'Dihapus dari favorit' : 'Ditambahkan ke favorit');
  };

  const handleViewDetails = () => {
    // Navigate to book details page
    window.location.href = `/books/${book.id}`;
  };

  const isAvailable = book.available_quantity > 0;
  const totalLoans = book.total_loans || book.loans_count || 0;

  return (
    <div className="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
      {/* Book Cover Placeholder */}
      <div className="relative h-48 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
        <BookOpen className="h-16 w-16 text-white opacity-80" />

        {/* Availability Badge */}
        <div className={`absolute top-2 left-2 px-2 py-1 rounded-full text-xs font-medium ${
          isAvailable
            ? 'bg-green-500 text-white'
            : 'bg-red-500 text-white'
        }`}>
          {isAvailable ? `${book.available_quantity} tersedia` : 'Habis'}
        </div>

        {/* Like Button */}
        {showActions && (
          <button
            onClick={handleLike}
            className={`absolute top-2 right-2 p-2 rounded-full transition-colors ${
              liked
                ? 'bg-red-500 text-white'
                : 'bg-white bg-opacity-20 text-white hover:bg-opacity-30'
            }`}
          >
            <Heart className={`h-4 w-4 ${liked ? 'fill-current' : ''}`} />
          </button>
        )}

        {/* Popularity Indicator */}
        {totalLoans > 0 && (
          <div className="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
            📈 {totalLoans}x dipinjam
          </div>
        )}
      </div>

      {/* Book Information */}
      <div className="p-4">
        {/* Title */}
        <h3 className="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">
          {book.title}
        </h3>

        {/* Author */}
        <div className="flex items-center text-gray-600 mb-2">
          <User className="h-4 w-4 mr-1" />
          <span className="text-sm truncate">{book.author}</span>
        </div>

        {/* Metadata */}
        <div className="space-y-1 mb-4">
          {book.published_year && (
            <div className="flex items-center text-gray-500 text-sm">
              <Calendar className="h-3 w-3 mr-1" />
              <span>{book.published_year}</span>
            </div>
          )}

          {book.isbn && (
            <div className="flex items-center text-gray-500 text-sm">
              <Hash className="h-3 w-3 mr-1" />
              <span className="truncate">{book.isbn}</span>
            </div>
          )}

          {book.category && (
            <div className="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
              {book.category}
            </div>
          )}
        </div>

        {/* Stock Information */}
        <div className="flex justify-between items-center text-sm text-gray-600 mb-4">
          <span>
            Stok: {book.available_quantity}/{book.quantity}
          </span>
          {book.created_at && (
            <span className="text-xs">
              Ditambahkan: {new Date(book.created_at).toLocaleDateString('id-ID')}
            </span>
          )}
        </div>

        {/* Actions */}
        {showActions && (
          <div className="flex gap-2">
            {/* Borrow Button */}
            <button
              onClick={handleBorrow}
              disabled={!isAvailable || borrowing}
              className={`flex-1 px-4 py-2 rounded-lg font-medium text-sm transition-colors ${
                isAvailable && !borrowing
                  ? 'bg-blue-600 text-white hover:bg-blue-700'
                  : 'bg-gray-300 text-gray-500 cursor-not-allowed'
              }`}
            >
              {borrowing ? (
                <div className="flex items-center justify-center">
                  <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                  Meminjam...
                </div>
              ) : isAvailable ? (
                'Pinjam'
              ) : (
                'Tidak Tersedia'
              )}
            </button>

            {/* View Details Button */}
            <button
              onClick={handleViewDetails}
              className="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              title="Lihat Detail"
            >
              <Eye className="h-4 w-4" />
            </button>
          </div>
        )}

        {/* Additional Info for Recommendations */}
        {book.recommendation_reason && (
          <div className="mt-3 p-2 bg-blue-50 rounded text-xs text-blue-700">
            💡 {book.recommendation_reason}
          </div>
        )}

        {/* Trending Badge */}
        {book.growth_rate && book.growth_rate > 0 && (
          <div className="mt-2 flex items-center text-xs text-green-600">
            <TrendingUp className="h-3 w-3 mr-1" />
            Trending +{book.growth_rate}%
          </div>
        )}

        {/* New Badge */}
        {book.days_since_added && book.days_since_added <= 7 && (
          <div className="mt-2 inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
            🆕 Buku Baru
          </div>
        )}
      </div>
    </div>
  );
};

export default BookCard;