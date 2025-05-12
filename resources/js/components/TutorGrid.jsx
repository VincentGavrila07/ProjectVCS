import React, { useEffect, useState } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/autoplay";
import { FaClock, FaThumbsUp } from "react-icons/fa";
import { motion } from "framer-motion";

const TutorSlider = () => {
    const [tutors, setTutors] = useState([]);

    useEffect(() => {
        fetch("/api/landing/tutors")
            .then((response) => response.json())
            .then((data) => setTutors(data))
            .catch((error) => console.error("Error fetching tutors:", error));
    }, []);

    return (
        <section id="tutor">
            <div className="container mx-auto py-10 px-6 text-center">
                <motion.h3
                    className="text-3xl md:text-4xl font-extrabold text-blue-600 mb-6"
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 1.5 }}
                >
                    MEET THE TUTOR
                </motion.h3>

                {tutors.length === 0 ? (
                    <motion.p
                        className="text-lg text-gray-600 mt-6"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 1 }}
                    >
                        Belum ada tutor yang sedang online
                    </motion.p>
                ) : (
                    <Swiper
                        modules={[Autoplay]}
                        autoplay={{ delay: 3000, disableOnInteraction: false }}
                        spaceBetween={20}
                        slidesPerView={1.2}
                        breakpoints={{
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 },
                        }}
                        loop
                    >
                        {tutors.map((tutor) => (
                            <SwiperSlide key={tutor.id}>
                                <motion.div
                                    className="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center"
                                    whileHover={{ scale: 1.05 }}
                                    transition={{ duration: 0.3 }}
                                >
                                    <img
                                        src={tutor.image.replace(/\\/g, "/") || "/images/default-tutor.jpg"}
                                        alt={tutor.name}
                                        className="w-24 h-24 object-cover rounded-full border-4 border-blue-500 mb-4"
                                    />
                                    <h3 className="font-semibold text-lg text-gray-800">{tutor.name}</h3>
                                    <p className="text-gray-600 text-sm">{tutor.specialty}</p>
                                    <div className="flex items-center text-gray-500 text-xs mt-2">
                                        <span className="flex items-center mr-3">
                                            <FaClock className="mr-1" /> {tutor.experience} 
                                        </span>
                                        <span className="flex items-center">
                                            {Array.from({ length: 5 }).map((_, index) => {
                                                const isFullStar = index < Math.floor(tutor.Rating);
                                                const isHalfStar = index === Math.floor(tutor.Rating) && tutor.Rating % 1 !== 0;
                                                return (
                                                    <span key={index} className="text-lg">
                                                        {isFullStar ? (
                                                            <span className="text-yellow-500">★</span>
                                                        ) : isHalfStar ? (
                                                            <span className="text-yellow-500">☆</span>
                                                        ) : (
                                                            <span className="text-gray-300">★</span>
                                                        )}
                                                    </span>
                                                );
                                            })}
                                            <span className="ml-2 text-gray-700">({tutor.Rating.toFixed(1)})</span>
                                        </span>
                                    </div>
                                    <div className="mt-2 text-blue-600 font-bold text-lg">
                                        Rp {tutor.price.toLocaleString("id-ID")} <span className="text-gray-500 line-through"> Rp {(tutor.price * 1.2).toLocaleString("id-ID")} </span>
                                    </div>
                                    <a href="/login" className="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition-all">
                                        Chat
                                    </a>
                                </motion.div>
                            </SwiperSlide>
                        ))}
                    </Swiper>
                )}
            </div>
        </section>
    );
};

export default TutorSlider;
