import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import Logo from "../../../public/images/LogoVcs.png";
import { Menu, X } from "lucide-react";
import { Link } from "react-scroll";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolling, setScrolling] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolling(window.scrollY > 50);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Mencegah scrolling saat menu terbuka
  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden"; // Kunci scroll
    } else {
      document.body.style.overflow = "auto"; // Aktifkan kembali scroll
    }
  }, [isOpen]);

  return (
    <motion.nav
      initial={{ y: -100, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 1, ease: "easeOut" }}
      className={`fixed w-full top-0 z-50 transition-all duration-300 ${
        scrolling ? "bg-[#0A2351]/80 backdrop-blur-md shadow-lg" : "bg-transparent"
      }`}
    >
      <div className="container mx-auto px-6 py-4 flex justify-between items-center">
        {/* Logo */}
        <a href="#" className="flex items-center space-x-2">
          <img src={Logo} alt="Logo" className="h-20 transition-transform hover:scale-110 duration-300" />
        </a>

        {/* Desktop Menu */}
        <ul className="hidden md:flex space-x-8 text-white text-lg font-medium">
          {[
            { name: "Home", to: "home" },
            { name: "About Us", to: "about" },
            { name: "Tutors", to: "tutor" },
            { name: "Contact Us", to: "contact" },
            { name: "Forum", to: "forum" }
          ].map((item, index) => (
            <motion.li
              key={index}
              whileHover={{ scale: 1.1, y: -2 }}
              transition={{ type: "spring", stiffness: 300 }}
            >
              <Link
                to={item.to}
                smooth={true}
                duration={800}
                offset={-80} // Untuk menyesuaikan jika ada navbar fixed
                className="relative cursor-pointer hover:text-blue-700 after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-blue-700 after:transition-all after:duration-300 hover:after:w-full"
              >
                {item.name}
              </Link>
            </motion.li>
          ))}
        </ul>

        {/* Button Get Started */}
        <motion.a
          href="/login"
          className="hidden md:block bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition-transform hover:scale-105"
          whileHover={{ scale: 1.1 }}
        >
          Masuk / Daftar
        </motion.a>

        {/* Mobile Menu Button */}
        <button
          className="md:hidden text-white focus:outline-none text-3xl z-50 relative"
          onClick={() => setIsOpen(!isOpen)}
        >
          {isOpen ? <X size={32} /> : <Menu size={32} />}
        </button>
      </div>

      {/* Mobile Menu */}
      <motion.div
        initial={{ x: "100%" }}
        animate={{ x: isOpen ? "0%" : "100%" }}
        transition={{ duration: 0.3 }}
        className={`fixed inset-0 bg-black/80 z-40 flex justify-center items-center transform ${
          isOpen ? "translate-x-0" : "translate-x-full"
        } transition-all duration-300`}
      >
       <motion.div
        initial={{ opacity: 0, y: -20 }}
        animate={{ opacity: isOpen ? 1 : 0, y: isOpen ? 0 : -20 }}
        transition={{ duration: 0.3 }}
        className="bg-white w-3/4 md:w-1/2 p-8 rounded-lg shadow-lg"
        >
            <ul className="flex flex-col space-y-6 text-gray-900 text-xl font-semibold text-center">
                {["Home", "About Us", "Services", "Contact Us", "Pages"].map((item, index) => (
                <li key={index}>
                    <a
                    href="#"
                    className="block py-2 transition-colors duration-300 hover:text-blue-600"
                    onClick={() => setIsOpen(false)}
                    >
                    {item}
                    </a>
                </li>
                ))}
                <li>
                <a
                    href="/login"
                    className="block text-center bg-blue-600 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300"
                    onClick={() => setIsOpen(false)}
                >
                    Masuk / Daftar
                </a>
                </li>
            </ul>
        </motion.div>
      </motion.div>
    </motion.nav>
  );
};

export default Navbar;
