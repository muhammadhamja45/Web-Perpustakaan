import React, { useState, useEffect } from 'react';
import { Bell, X, Check, CheckCheck, Trash2, Settings, Filter } from 'lucide-react';
import { notificationsAPI } from '../services/api';
import { toast } from 'react-hot-toast';
import { formatDistanceToNow } from 'date-fns';
import { id } from 'date-fns/locale';

const NotificationCenter = ({ isOpen, onClose }) => {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(false);
  const [filter, setFilter] = useState('all'); // all, unread, read
  const [stats, setStats] = useState({});

  useEffect(() => {
    if (isOpen) {
      loadNotifications();
      loadStats();
    }
  }, [isOpen, filter]);

  const loadNotifications = async () => {
    setLoading(true);
    try {
      const params = filter === 'unread' ? { unread_only: true } : {};
      const response = await notificationsAPI.getAll(params);
      setNotifications(response.data.data);
    } catch (error) {
      console.error('Failed to load notifications:', error);
      toast.error('Gagal memuat notifikasi');
    } finally {
      setLoading(false);
    }
  };

  const loadStats = async () => {
    try {
      const response = await notificationsAPI.getStats();
      setStats(response.data.data);
    } catch (error) {
      console.error('Failed to load notification stats:', error);
    }
  };

  const markAsRead = async (notificationId) => {
    try {
      await notificationsAPI.markAsRead(notificationId);
      setNotifications(notifications.map(notif =>
        notif.id === notificationId
          ? { ...notif, read_at: new Date().toISOString() }
          : notif
      ));
      setStats(prev => ({
        ...prev,
        unread_notifications: prev.unread_notifications - 1
      }));
      toast.success('Notifikasi ditandai sudah dibaca');
    } catch (error) {
      toast.error('Gagal menandai notifikasi');
    }
  };

  const markAllAsRead = async () => {
    try {
      const response = await notificationsAPI.markAllAsRead();
      setNotifications(notifications.map(notif => ({
        ...notif,
        read_at: notif.read_at || new Date().toISOString()
      })));
      setStats(prev => ({
        ...prev,
        unread_notifications: 0
      }));
      toast.success(`${response.data.marked_count} notifikasi ditandai sudah dibaca`);
    } catch (error) {
      toast.error('Gagal menandai semua notifikasi');
    }
  };

  const deleteNotification = async (notificationId) => {
    try {
      await notificationsAPI.delete(notificationId);
      setNotifications(notifications.filter(notif => notif.id !== notificationId));
      if (!notifications.find(n => n.id === notificationId)?.read_at) {
        setStats(prev => ({
          ...prev,
          unread_notifications: prev.unread_notifications - 1,
          total_notifications: prev.total_notifications - 1
        }));
      }
      toast.success('Notifikasi dihapus');
    } catch (error) {
      toast.error('Gagal menghapus notifikasi');
    }
  };

  const getNotificationIcon = (type) => {
    switch (type) {
      case 'loan_created':
        return '📚';
      case 'loan_reminder':
        return '⏰';
      case 'loan_overdue':
        return '⚠️';
      case 'loan_returned':
        return '✅';
      case 'new_book':
        return '🆕';
      case 'book_available':
        return '📖';
      default:
        return '📢';
    }
  };

  const getNotificationColor = (type) => {
    switch (type) {
      case 'loan_created':
        return 'bg-blue-50 border-blue-200';
      case 'loan_reminder':
        return 'bg-yellow-50 border-yellow-200';
      case 'loan_overdue':
        return 'bg-red-50 border-red-200';
      case 'loan_returned':
        return 'bg-green-50 border-green-200';
      case 'new_book':
        return 'bg-purple-50 border-purple-200';
      default:
        return 'bg-gray-50 border-gray-200';
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-hidden">
      <div className="absolute inset-0 bg-black bg-opacity-50" onClick={onClose} />

      <div className="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl">
        {/* Header */}
        <div className="flex items-center justify-between p-4 border-b border-gray-200">
          <div className="flex items-center gap-2">
            <Bell className="h-5 w-5 text-gray-600" />
            <h2 className="text-lg font-semibold text-gray-900">Notifikasi</h2>
            {stats.unread_notifications > 0 && (
              <span className="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                {stats.unread_notifications}
              </span>
            )}
          </div>
          <button
            onClick={onClose}
            className="p-1 hover:bg-gray-100 rounded-full"
          >
            <X className="h-5 w-5 text-gray-500" />
          </button>
        </div>

        {/* Stats */}
        <div className="p-4 bg-gray-50 border-b border-gray-200">
          <div className="grid grid-cols-3 gap-4 text-center">
            <div>
              <div className="text-lg font-semibold text-gray-900">
                {stats.total_notifications || 0}
              </div>
              <div className="text-xs text-gray-500">Total</div>
            </div>
            <div>
              <div className="text-lg font-semibold text-blue-600">
                {stats.unread_notifications || 0}
              </div>
              <div className="text-xs text-gray-500">Belum dibaca</div>
            </div>
            <div>
              <div className="text-lg font-semibold text-green-600">
                {stats.today_notifications || 0}
              </div>
              <div className="text-xs text-gray-500">Hari ini</div>
            </div>
          </div>
        </div>

        {/* Controls */}
        <div className="p-4 border-b border-gray-200">
          {/* Filter */}
          <div className="flex items-center gap-2 mb-3">
            <Filter className="h-4 w-4 text-gray-400" />
            <select
              value={filter}
              onChange={(e) => setFilter(e.target.value)}
              className="flex-1 text-sm border border-gray-300 rounded-lg px-2 py-1"
            >
              <option value="all">Semua Notifikasi</option>
              <option value="unread">Belum Dibaca</option>
              <option value="read">Sudah Dibaca</option>
            </select>
          </div>

          {/* Actions */}
          <div className="flex gap-2">
            <button
              onClick={markAllAsRead}
              disabled={stats.unread_notifications === 0}
              className="flex items-center gap-1 px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <CheckCheck className="h-3 w-3" />
              Tandai Semua
            </button>
            <button
              onClick={() => {/* Open settings */}}
              className="flex items-center gap-1 px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              <Settings className="h-3 w-3" />
              Pengaturan
            </button>
          </div>
        </div>

        {/* Notifications List */}
        <div className="flex-1 overflow-y-auto">
          {loading ? (
            <div className="flex justify-center items-center py-8">
              <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
          ) : notifications.length === 0 ? (
            <div className="text-center py-8">
              <Bell className="h-12 w-12 text-gray-300 mx-auto mb-3" />
              <p className="text-gray-500">
                {filter === 'unread' ? 'Tidak ada notifikasi baru' : 'Tidak ada notifikasi'}
              </p>
            </div>
          ) : (
            <div className="divide-y divide-gray-200">
              {notifications.map((notification) => (
                <div
                  key={notification.id}
                  className={`p-4 hover:bg-gray-50 transition-colors ${
                    !notification.read_at ? 'bg-blue-50' : ''
                  }`}
                >
                  <div className="flex items-start gap-3">
                    {/* Icon */}
                    <div className="flex-shrink-0 text-lg">
                      {getNotificationIcon(notification.type)}
                    </div>

                    {/* Content */}
                    <div className="flex-1 min-w-0">
                      <div className="flex items-start justify-between">
                        <div className="flex-1">
                          <h4 className={`text-sm font-medium ${
                            !notification.read_at ? 'text-gray-900' : 'text-gray-700'
                          }`}>
                            {notification.title}
                          </h4>
                          <p className="text-sm text-gray-600 mt-1">
                            {notification.message}
                          </p>
                          <p className="text-xs text-gray-400 mt-2">
                            {formatDistanceToNow(new Date(notification.created_at), {
                              addSuffix: true,
                              locale: id
                            })}
                          </p>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center gap-1 ml-2">
                          {!notification.read_at && (
                            <button
                              onClick={() => markAsRead(notification.id)}
                              className="p-1 hover:bg-gray-200 rounded"
                              title="Tandai sudah dibaca"
                            >
                              <Check className="h-3 w-3 text-gray-500" />
                            </button>
                          )}
                          <button
                            onClick={() => deleteNotification(notification.id)}
                            className="p-1 hover:bg-gray-200 rounded"
                            title="Hapus notifikasi"
                          >
                            <Trash2 className="h-3 w-3 text-gray-500" />
                          </button>
                        </div>
                      </div>

                      {/* Action Button */}
                      {notification.data?.action_url && (
                        <button
                          onClick={() => {
                            window.location.href = notification.data.action_url;
                          }}
                          className="mt-2 text-xs text-blue-600 hover:text-blue-700 underline"
                        >
                          Lihat Detail
                        </button>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default NotificationCenter;