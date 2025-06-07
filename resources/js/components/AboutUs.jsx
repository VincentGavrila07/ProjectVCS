import React from "react";
import { motion } from "framer-motion";
import studyingSession from "../../../public/images/AboutUsImage.jpg"; // Pastikan path gambar benar
import { useState } from "react";
import { useEffect } from "react";
const teamMembers = [
    { id: 1, name: "Vincent Gavrila", role: "CEO AND FOUNDER", image: "/images/vincent.jpeg" },
    { id: 2, name: "Belmiro Kayru", role: "CEO AND FOUNDER", image: "/images/papgantenghot.jpeg" },
    { id: 3, name: "Edmund Setiady", role: "CEO AND FOUNDER", image: "/images/edmund.jpg" },
    { id: 4, name: "Rafael Dillon", role: "CEO AND FOUNDER", image: "/images/dillon.jpg" },
    { id: 5, name: "Bernardus William", role: "CEO AND FOUNDER", image: "/images/william.jpg" },
];
const AboutUs = () => {
  const [isOpen, setIsOpen] = useState(false);
    useEffect(() => {
    if (isOpen) {
      // Disable scroll
      document.body.style.overflow = "hidden";
    } else {
      // Enable scroll kembali
      document.body.style.overflow = "";
    }

    // Cleanup saat component unmount / isOpen berubah
    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);
  
    const lorem = `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod...`; // akan diganti di bawah
  
    const paragraph = `Perkembangan teknologi digital telah mengubah cara kita belajar secara signifikan. Di tengah kemajuan tersebut, Video Call Study hadir sebagai solusi pembelajaran daring yang menawarkan metode belajar interaktif dan fleksibel melalui video call secara real-time. Kami percaya bahwa proses belajar yang efektif membutuhkan interaksi langsung antara siswa dan tutor agar materi dapat dipahami lebih baik, serta penyesuaian waktu belajar yang sesuai dengan jadwal individu. Oleh karena itu, VCS dirancang untuk memberikan pengalaman belajar yang personal dan menyenangkan, tanpa batasan ruang dan waktu.`

    const paragraph2 = `Kami berkomitmen untuk menghadirkan platform pembelajaran yang tidak hanya menyediakan materi secara pasif, tetapi juga memfasilitasi komunikasi dua arah antara siswa dan pengajar. Dengan fitur penjadwalan yang fleksibel dan integrasi teknologi video call, VCS memudahkan siswa dalam mendapatkan bimbingan yang fokus dan sesuai kebutuhan mereka. Melalui inovasi ini, kami berharap dapat mendukung transformasi pendidikan digital yang lebih manusiawi dan inklusif, sekaligus membuka kesempatan bagi siapa saja untuk belajar dengan cara yang lebih efektif dan menyenangkan.`
  return (
    <section id="about" className="relative w-full min-h-screen bg-gray-50">
      {/* Background Section */}
      <div className="relative flex min-h-screen flex-col md:flex-row">
        <div className="w-full md:w-1/2 bg-black bg-opacity-70 flex items-center justify-center text-white p-12">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 1.5 }}
            viewport={{ once: false }}
            className="max-w-lg"
          >
            <h2 className="text-4xl md:text-5xl font-extrabold text-white leading-tight">
              ABOUT <span className="text-blue-400">VIDEO CALL STUDY</span>
            </h2>
            <p className="mt-6 text-base md:text-lg text-gray-300 leading-relaxed">
              Video Call Study is a platform that connects students and tutors for an enhanced virtual learning experience. Our mission is to create an interactive, engaging, and productive study environment.
            </p>
            <button
              onClick={() => setIsOpen(true)}
              className="inline-block mt-6 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-all"
            >
              Learn More
            </button>

            {isOpen && (
              <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div className="bg-white max-w-lg w-full max-h-[80vh] rounded-lg shadow-lg overflow-y-auto p-6 relative">
                  <button
                    onClick={() => setIsOpen(false)}
                    className="absolute top-2 right-2 text-gray-600 hover:text-black text-xl font-bold"
                  >
                    &times;
                  </button>
                  <h2 className="text-2xl font-bold mb-4 text-gray-800">More Information</h2>
                    <p className="mb-4 text-gray-800">{paragraph}</p>
                    <p className="text-gray-800">{paragraph2}</p>
                </div>
              </div>
            )}
          </motion.div>
        </div>

        {/* Image Section with Quote */}
        <div className="relative w-full md:w-1/2">
          <img src={studyingSession} alt="Studying Session" className="w-full h-full object-cover" />
          
          {/* Quote Overlay */}
          <motion.div
            className="absolute bottom-5 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-60 text-white text-center p-4 rounded-lg max-w-md shadow-md"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 1.5 }}
            viewport={{ once: false }}
          >
            <p className="text-base md:text-lg italic">
              "The present is theirs; the future, for which I really worked, is mine."
            </p>
            <p className="mt-2 text-gray-300 font-semibold">- Nikola Tesla</p>
          </motion.div>
        </div>
      </div>

      {/* The Team Section */}
      <div className="relative mt-16 md:mt-32 text-center px-4 md:px-12 py-10 md:py-20 bg-white shadow-md rounded-lg">
        <motion.h3
          className="text-3xl md:text-4xl font-extrabold text-blue-600"
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1.5 }}
          viewport={{ once: false }}
        >
          MEET THE TEAM
        </motion.h3>
        <p className="text-gray-600 mt-4 max-w-3xl mx-auto text-base md:text-lg">
          Our dedicated team works tirelessly to ensure the best learning experience for students worldwide.
        </p>

        <div className="mt-8 md:mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 md:gap-10 ">
          {teamMembers.map((member) => (
            <motion.div
            key={member.id}
            className="relative p-4 md:p-6 text-center transition-all duration-500 hover:shadow-2xl  cursor-pointer shadow-md"
            viewport={{ once: false }}
          >
            {/* Foto Profil dengan rounded hanya di bagian bawah */}
            <motion.img
              src={member.image}
              alt={member.name}
              viewport={{ once: false }}
              className="w-48 h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 object-cover mx-auto mb-4"
              style={{ 
                borderTopLeftRadius: "0",
                borderTopRightRadius: "0",
                borderBottomLeftRadius: "0rem", // Sesuaikan nilai rounded
                borderBottomRightRadius: "0rem", // Sesuaikan nilai rounded
              }}
            />
          
            {/* Nama & Role */}
            <h4 className="text-base md:text-lg font-semibold text-gray-600 transition-colors duration-300 hover:text-blue-500">
            {member.name}
            </h4>
            <p className="text-sm text-gray-600 font-medium mb-12">- {member.role} -</p>
          
            {/* Kotak Biru dengan Ikon Media Sosial */}
            <div className="absolute bottom-0 left-0 right-0 p-4  bg-[#0A2351]/80">
                <div className="flex justify-center space-x-4">
                    {/* Ikon Instagram */}
                    <a href="#" target="_blank" rel="noopener noreferrer">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6 text-white hover:text-blue-200 transition-colors"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                    </a>

                    {/* Ikon X (Twitter) */}
                    <a href="#" target="_blank" rel="noopener noreferrer">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6 text-white hover:text-blue-200 transition-colors"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                    </svg>
                    </a>

                    {/* Ikon TikTok */}
                    <a href="#" target="_blank" rel="noopener noreferrer">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6 text-white hover:text-blue-200 transition-colors"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 0 1 3.183-4.51v-3.5a6.329 6.329 0 0 0-5.394 10.692 6.33 6.33 0 0 0 10.857-4.424V8.687a8.182 8.182 0 0 0 4.773 1.526V6.79a4.831 4.831 0 0 1-1.003-.104z" />
                    </svg>
                    </a>
                </div>
            </div>
          </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default AboutUs;