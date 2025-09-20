import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { Search, Filter, X, BookOpen, Calendar, User, Hash } from 'lucide-react';
import { searchAPI } from '../services/api';
import { toast } from 'react-hot-toast';
import BookCard from './BookCard';
import LoadingSpinner from './LoadingSpinner';

const BookSearchAdvanced = () => {
  const [searchResults, setSearchResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [showFilters, setShowFilters] = useState(false);
  const [facets, setFacets] = useState(null);
  const [pagination, setPagination] = useState(null);
  const [suggestions, setSuggestions] = useState([]);
  const [showSuggestions, setShowSuggestions] = useState(false);

  const { register, handleSubmit, watch, reset, setValue } = useForm({
    defaultValues: {
      title: '',
      author: '',
      isbn: '',
      category: '',
      year_from: '',
      year_to: '',
      available_only: false,
      sort_by: 'title',
      sort_order: 'asc',
      per_page: 12
    }
  });

  const watchedFields = watch();

  // Load facets on component mount
  useEffect(() => {
    loadFacets();
  }, []);

  // Auto-complete functionality
  useEffect(() => {
    const debounceTimer = setTimeout(() => {
      if (watchedFields.title && watchedFields.title.length >= 2) {
        loadSuggestions(watchedFields.title, 'title');
      } else if (watchedFields.author && watchedFields.author.length >= 2) {
        loadSuggestions(watchedFields.author, 'author');
      } else {
        setSuggestions([]);
        setShowSuggestions(false);
      }
    }, 300);

    return () => clearTimeout(debounceTimer);
  }, [watchedFields.title, watchedFields.author]);

  const loadFacets = async () => {
    try {
      const response = await searchAPI.getFacetedSearch('');
      setFacets(response.data.data.facets);
    } catch (error) {
      console.error('Failed to load facets:', error);
    }
  };

  const loadSuggestions = async (query, type) => {
    try {
      const response = await searchAPI.getSuggestions(query, type);
      setSuggestions(response.data.data.suggestions);
      setShowSuggestions(true);
    } catch (error) {
      console.error('Failed to load suggestions:', error);
    }
  };

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      // Filter out empty values
      const filters = Object.entries(data).reduce((acc, [key, value]) => {
        if (value !== '' && value !== false) {
          acc[key] = value;
        }
        return acc;
      }, {});

      const response = await searchAPI.advancedSearch(filters);
      setSearchResults(response.data.data.data);
      setPagination(response.data.data.pagination);
      setShowSuggestions(false);

      if (response.data.data.data.length === 0) {
        toast.info('Tidak ada buku yang ditemukan dengan kriteria tersebut');
      } else {
        toast.success(`Ditemukan ${response.data.data.pagination.total} buku`);
      }
    } catch (error) {
      toast.error('Gagal melakukan pencarian');
      console.error('Search error:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleQuickSearch = async (query) => {
    setLoading(true);
    try {
      const response = await searchAPI.searchBooks(query, { limit: 20 });
      setSearchResults(response.data.data);
      setPagination(null);
      setValue('title', query);
      setShowSuggestions(false);

      toast.success(`Ditemukan ${response.data.data.length} buku`);
    } catch (error) {
      toast.error('Gagal melakukan pencarian');
    } finally {
      setLoading(false);
    }
  };

  const clearFilters = () => {
    reset();
    setSearchResults([]);
    setPagination(null);
    setShowSuggestions(false);
  };

  const selectSuggestion = (suggestion) => {
    if (typeof suggestion === 'string') {
      setValue('title', suggestion);
      handleQuickSearch(suggestion);
    } else {
      if (suggestion.type === 'title') {
        setValue('title', suggestion.value);
      } else if (suggestion.type === 'author') {
        setValue('author', suggestion.value);
      }
      setShowSuggestions(false);
    }
  };

  return (
    <div className="max-w-7xl mx-auto p-6">
      {/* Header */}
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-2">
          Pencarian Buku Lanjutan
        </h1>
        <p className="text-gray-600">
          Temukan buku dengan kriteria pencarian yang spesifik
        </p>
      </div>

      {/* Search Form */}
      <div className="bg-white rounded-lg shadow-md p-6 mb-8">
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
          {/* Quick Search Row */}
          <div className="relative">
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Pencarian Cepat
            </label>
            <div className="relative">
              <Search className="absolute left-3 top-3 h-5 w-5 text-gray-400" />
              <input
                {...register('title')}
                type="text"
                placeholder="Masukkan judul buku..."
                className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                onFocus={() => suggestions.length > 0 && setShowSuggestions(true)}
              />
            </div>

            {/* Suggestions Dropdown */}
            {showSuggestions && suggestions.length > 0 && (
              <div className="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                {suggestions.map((suggestion, index) => (
                  <button
                    key={index}
                    type="button"
                    className="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center gap-2"
                    onClick={() => selectSuggestion(suggestion)}
                  >
                    {typeof suggestion === 'string' ? (
                      <>
                        <BookOpen className="h-4 w-4 text-gray-400" />
                        <span>{suggestion}</span>
                      </>
                    ) : (
                      <>
                        {suggestion.type === 'title' && <BookOpen className="h-4 w-4 text-gray-400" />}
                        {suggestion.type === 'author' && <User className="h-4 w-4 text-gray-400" />}
                        <span>{suggestion.value}</span>
                        <span className="text-xs text-gray-500 ml-auto capitalize">
                          {suggestion.type}
                        </span>
                      </>
                    )}
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Advanced Filters Toggle */}
          <div className="flex justify-between items-center">
            <button
              type="button"
              onClick={() => setShowFilters(!showFilters)}
              className="flex items-center gap-2 text-blue-600 hover:text-blue-700"
            >
              <Filter className="h-4 w-4" />
              {showFilters ? 'Sembunyikan Filter' : 'Tampilkan Filter Lanjutan'}
            </button>

            <div className="flex gap-2">
              <button
                type="button"
                onClick={clearFilters}
                className="flex items-center gap-2 px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
              >
                <X className="h-4 w-4" />
                Reset
              </button>
              <button
                type="submit"
                disabled={loading}
                className="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
              >
                <Search className="h-4 w-4" />
                {loading ? 'Mencari...' : 'Cari'}
              </button>
            </div>
          </div>

          {/* Advanced Filters */}
          {showFilters && (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
              {/* Author Filter */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Penulis
                </label>
                <div className="relative">
                  <User className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    {...register('author')}
                    type="text"
                    placeholder="Nama penulis"
                    className="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              {/* ISBN Filter */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  ISBN
                </label>
                <div className="relative">
                  <Hash className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    {...register('isbn')}
                    type="text"
                    placeholder="ISBN buku"
                    className="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              {/* Category Filter */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Kategori
                </label>
                <select
                  {...register('category')}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Semua Kategori</option>
                  {facets?.categories?.map((category) => (
                    <option key={category.category} value={category.category}>
                      {category.category} ({category.count})
                    </option>
                  ))}
                </select>
              </div>

              {/* Year Range */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Tahun Dari
                </label>
                <div className="relative">
                  <Calendar className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    {...register('year_from')}
                    type="number"
                    min="1900"
                    max={new Date().getFullYear()}
                    placeholder="1990"
                    className="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Tahun Sampai
                </label>
                <div className="relative">
                  <Calendar className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    {...register('year_to')}
                    type="number"
                    min="1900"
                    max={new Date().getFullYear()}
                    placeholder={new Date().getFullYear().toString()}
                    className="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              {/* Sort Options */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Urutkan berdasarkan
                </label>
                <select
                  {...register('sort_by')}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                  <option value="title">Judul</option>
                  <option value="author">Penulis</option>
                  <option value="published_year">Tahun Terbit</option>
                  <option value="total_loans">Popularitas</option>
                  <option value="created_at">Tanggal Ditambahkan</option>
                </select>
              </div>

              {/* Availability Filter */}
              <div className="flex items-center">
                <input
                  {...register('available_only')}
                  type="checkbox"
                  className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                />
                <label className="ml-2 text-sm text-gray-700">
                  Hanya buku yang tersedia
                </label>
              </div>
            </div>
          )}
        </form>
      </div>

      {/* Search Results */}
      {loading && (
        <div className="flex justify-center py-8">
          <LoadingSpinner />
        </div>
      )}

      {searchResults.length > 0 && (
        <div>
          {/* Results Header */}
          <div className="flex justify-between items-center mb-6">
            <h2 className="text-xl font-semibold text-gray-900">
              Hasil Pencarian
              {pagination && (
                <span className="text-gray-500 font-normal ml-2">
                  ({pagination.total} buku ditemukan)
                </span>
              )}
            </h2>
          </div>

          {/* Results Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {searchResults.map((book) => (
              <BookCard key={book.id} book={book} />
            ))}
          </div>

          {/* Pagination */}
          {pagination && pagination.last_page > 1 && (
            <div className="mt-8 flex justify-center">
              <div className="flex items-center gap-2">
                <span className="text-sm text-gray-700">
                  Halaman {pagination.current_page} dari {pagination.last_page}
                </span>
              </div>
            </div>
          )}
        </div>
      )}

      {searchResults.length === 0 && !loading && (
        <div className="text-center py-12">
          <BookOpen className="h-16 w-16 text-gray-300 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">
            Belum ada pencarian
          </h3>
          <p className="text-gray-500">
            Masukkan kata kunci untuk mencari buku yang Anda inginkan
          </p>
        </div>
      )}
    </div>
  );
};

export default BookSearchAdvanced;