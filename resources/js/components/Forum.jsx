import React, { useEffect, useState } from 'react';
import { HiChevronLeft, HiChevronRight } from "react-icons/hi";
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/id'; // kalau kamu mau pakai Bahasa Indonesia juga
import { motion } from "framer-motion";
dayjs.extend(relativeTime);
dayjs.locale('en'); // atau 'id'

const getRelativeTime = (date) => {
  const now = dayjs();
  const posted = dayjs(date);
  const diffInDays = now.diff(posted, 'day');
  const diffInWeeks = now.diff(posted, 'week');
  const diffInMonths = now.diff(posted, 'month');
  const diffInYears = now.diff(posted, 'year');

  if (diffInDays < 7) {
    return posted.fromNow(); // gunakan "x days ago"
  } else if (diffInDays < 30) {
    return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''} ago`;
  } else if (diffInDays < 365) {
    return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''} ago`;
  } else {
    return `${diffInYears} year${diffInYears > 1 ? 's' : ''} ago`;
  }
};

const Forum = () => {
  const [threads, setThreads] = useState([]);
  const [pagination, setPagination] = useState({});
  const [currentPage, setCurrentPage] = useState(1);

  const fetchThreads = (page = 1) => {
    fetch(`/api/landing/threads?page=${page}`)
      .then(res => res.json())
      .then(data => {
        setThreads(data.data);
        setPagination({
          current_page: data.current_page,
          last_page: data.last_page,
        });
      })
      .catch(error => {
        console.error("Gagal mengambil data thread:", error);
      });
  };

  useEffect(() => {
    fetchThreads(currentPage);
  }, [currentPage]);

  const goToPage = (page) => {
    if (page !== currentPage && page >= 1 && page <= pagination.last_page) {
      setCurrentPage(page);
    }
  };

  return (
    <section id="forum" className="py-10 px-4 max-w-5xl mx-auto ">
        <motion.h3
          className="text-3xl md:text-4xl font-extrabold text-blue-600 text-center"
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1.5 }}
          viewport={{ once: false }}
        >
          Public Thread
        </motion.h3>
      <div className="space-y-6 mt-12">
        {threads.length > 0 ? threads.map((thread) => (
          <div key={thread.id} className="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-100 hover:shadow-md transition duration-300">
            <span className={`inline-block ${thread.thread_subject ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600'} text-sm font-medium rounded-full px-3 py-1 mb-3`}>
              {thread.thread_subject || 'General'}
            </span>
            <h3
              onClick={() => window.location.href = '/login'}
              className="text-lg font-semibold text-blue-600 hover:underline cursor-pointer mb-2"
            >
              {thread.title}
            </h3>
            <p className="text-gray-600 mb-4">{thread.content.slice(0, 70)}...</p>
            <div className="flex justify-between items-center text-sm">
              <div className="flex items-center gap-3">
                <img src={`/storage/${thread.image}`} className="w-12 h-12 rounded-full border-4 border-blue-400 shadow-md object-cover" alt={thread.username} />
                <div>
                  <p className="text-gray-400">Ditulis oleh</p>
                  <p className="font-semibold text-gray-800">{thread.username} {thread.teacherid}</p>
                  {thread.role === 1 && (
                    <p className="text-sm text-gray-500">Mengajar {thread.user_subject || 'Tidak ada subject'}</p>
                  )}
                </div>
              </div>
              <div className="text-gray-400 text-sm flex items-center gap-1">
                <span>{getRelativeTime(thread.created_at)}</span>
              </div>
            </div>
          </div>
        )) : (
          <p className="text-gray-500 text-center">Belum ada thread.</p>
        )}
      </div>

      {/* Smart Pagination */}
      <div className="flex justify-center items-center gap-2 mt-8 flex-wrap">
        <button
          onClick={() => goToPage(currentPage - 1)}
          disabled={currentPage === 1}
          className={`px-3 py-1 rounded-full ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-blue-400 hover:text-white'}`}
        >
          <HiChevronLeft/>
        </button>

        {Array.from({ length: pagination.last_page || 0 }, (_, i) => i + 1)
          .filter(page => {
            return (
              page === 1 ||
              page === 2 ||
              page === pagination.last_page ||
              page === pagination.last_page - 1 ||
              (page >= currentPage - 1 && page <= currentPage + 1)
            );
          })
          .reduce((acc, page, idx, arr) => {
            if (idx > 0 && page - arr[idx - 1] > 1) {
              acc.push('...');
            }
            acc.push(page);
            return acc;
          }, [])
          .map((item, idx) =>
            item === '...' ? (
              <span key={`ellipsis-${idx}`} className="px-2 text-gray-400 select-none">...</span>
            ) : (
              <button
                key={item}
                onClick={() => goToPage(item)}
                className={`px-3 py-1 rounded-full ${
                  item === currentPage ? 'bg-blue-500 text-white font-bold' : 'bg-gray-200 text-gray-700 hover:bg-blue-400 hover:text-white'
                }`}
              >
                {item}
              </button>
            )
          )}

        <button
          onClick={() => goToPage(currentPage + 1)}
          disabled={currentPage === pagination.last_page}
          className={`px-3 py-1 rounded-full ${currentPage === pagination.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-blue-400 hover:text-white'}`}
        >
          <HiChevronRight/>
        </button>
      </div>
    </section>
  );
};

export default Forum;
