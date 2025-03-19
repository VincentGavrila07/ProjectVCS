import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";

const videos = [
  "/videos/Hero1.mp4",
  "/videos/Hero2.mp4",
  "videos/Hero3.mp4",
];

const HeroSection = () => {
  const [currentVideo, setCurrentVideo] = useState(0);
  const [fade, setFade] = useState(true);

  useEffect(() => {
    const interval = setInterval(() => {
      setFade(false); // Mulai fade out
      setTimeout(() => {
        setCurrentVideo((prev) => (prev + 1) % videos.length); // Ganti video
        setFade(true); // Mulai fade in
      }, 500); // Waktu fade out sebelum ganti video
    }, 5000); // Durasi tiap video sebelum berganti

    return () => clearInterval(interval);
  }, []);

  return (
    <section id="home" className="relative h-screen flex flex-col justify-center items-center text-center px-4 bg-black">
      {/* Video Background dengan transisi */}
      <motion.video
        key={currentVideo} // Gunakan key agar video berubah
        className="absolute inset-0 w-full h-full object-cover blur-sm"
        src={videos[currentVideo]}
        autoPlay
        loop
        muted
        playsInline
        initial={{ opacity: 0 }}
        animate={{ opacity: fade ? 1 : 0 }}
        transition={{ duration: 1.5 }}
        viewport={{ once: false }}
      />

      {/* Overlay Gelap */}
      <motion.div
        className="absolute inset-0 bg-black bg-opacity-50"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 1.5 }}
        viewport={{ once: false }}
      ></motion.div>

      {/* Konten Hero */}
      <motion.div
        className="relative z-10 max-w-3xl text-center mx-auto"
        initial={{ y: 30, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 1 }}
        viewport={{ once: false }}
      >
        <motion.h1
          className="text-3xl sm:text-5xl font-medium tracking-tight leading-tight text-white"
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1 }}
          viewport={{ once: false }}
        >
          Video Call Study
        </motion.h1>
        <motion.p
          className="text-md max-w-2xl mx-auto mt-4 text-white tracking-wide leading-7"
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1.2 }}
          viewport={{ once: false }}
        >
          Kami menghadirkan sentuhan elegan yang menyempurnakan keindahan dan kenyamanan setiap ruangan dengan desain yang berkelas.
        </motion.p>

        {/* Tombol CTA */}
        <motion.div
          className="mt-6 flex flex-wrap gap-4 justify-center"
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          transition={{ duration: 1 }}
          viewport={{ once: false }}
        >
          <motion.button
            className="px-4 py-2 sm:px-6 sm:py-2 bg-white text-black rounded-lg hover:bg-blue-600 hover:text-white transition font-bold"
            onClick={() => window.location.href = "/produk"}
          >
            VCS Sekarang
          </motion.button>

          <motion.button
            className="px-4 py-2 sm:px-6 sm:py-2 border border-white text-white rounded-lg hover:bg-blue-600 hover:text-white hover:border-none transition font-bold"
            onClick={() => window.open("https://wa.me/6281282018322", "_blank")}
          >
            Hubungi
          </motion.button>
        </motion.div>
      </motion.div>
    </section>
  );
};

export default HeroSection;
