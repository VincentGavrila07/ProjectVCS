import React from "react";
import { FaFacebookF, FaTwitter, FaLinkedinIn , FaMapMarkerAlt, FaPhoneAlt, FaEnvelope} from "react-icons/fa";

const Footer = () => {
  return (
    <section id="footer">

      <footer className="bg-gray-900 text-white py-10">
        <div className="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Kolom 1 */}
          <div>
            <h4 className="font-semibold mb-3">TENTANG KAMI</h4>
            <ul className="space-y-2 text-sm">
              <li><a href="#about" className="hover-underline">Profil Perusahaan</a></li>
              <li><a href="#" className="hover-underline">Kebijakan Privasi</a></li>
              <li><a href="#" className="hover-underline">Kontak</a></li>
            </ul>
          </div>

          {/* Kolom 2 */}
          <div>
            <h4 className="font-semibold mb-3">LAYANAN KAMI</h4>
            <ul className="space-y-2 text-sm">
              <li><a href="#tutor" className="hover-underline">Find Tutor</a></li>
              <li><a href="#forum" className="hover-underline">Public Thread</a></li>
            </ul>
          </div>

          {/* Kolom 3 */}
          {/* <div>
            <h4 className="font-semibold mb-3">BLOG</h4>
            <ul className="space-y-2 text-sm">
              <li><a href="#" className="hover-underline">Berita</a></li>
              <li><a href="#" className="hover-underline">Artikel</a></li>
            </ul>
          </div> */}

         {/* Kolom 4 */}
          <div>
            <h4 className="font-semibold mb-3">HUBUNGI KAMI</h4>
            <ul className="space-y-2 text-sm">
              <li className="flex items-center gap-2">
                <FaMapMarkerAlt className="text-blue-500" />
                Jl. Pendidikan No. 123, Jakarta
              </li>
              <li className="flex items-center gap-2">
                <FaPhoneAlt className="text-blue-500" />
                (021) 1234-5678
              </li>
              <li className="flex items-center gap-2">
                <FaEnvelope className="text-blue-500" />
                vcssupport@gmail.com
              </li>
            </ul>
          </div>

          {/* Kolom 5 */}
          <div>
            <h4 className="font-semibold mb-3">BERLANGGANAN</h4>
            <input
              type="email"
              placeholder="Masukkan email Anda"
              className="w-full p-2 text-black rounded-md"
            />
            <p className="text-xs mt-2 italic ">
              *Kami tidak akan mengirimkan spam ke email Anda.
            </p>
          </div>
        </div>

        {/* Footer Bawah */}
        <div className="border-t border-gray-700 mt-8 pt-4 px-6">
          <div className="container mx-auto flex flex-col md:flex-row justify-between items-start">
            {/* Hak Cipta dan Link */}
            <div className="text-sm">
              <p>© 2025 - Semua Hak Dilindungi</p>
            </div>

            {/* Ikon Sosial Media */}
            <div className="flex space-x-4 mt-4 md:mt-0">
              <FaFacebookF className="cursor-pointer hover:text-gray-300" />
              <FaTwitter className="cursor-pointer hover:text-gray-300" />
              <FaLinkedinIn className="cursor-pointer hover:text-gray-300" />
            </div>
          </div>
        </div>

        {/* Tambahkan CSS */}
        <style jsx>{`
          .hover-underline {
            position: relative;
            display: inline-block;
            text-decoration: none;
            color: inherit;
          }

          .hover-underline::after {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: white;
            transition: width 0.3s ease-in-out;
          }

          .hover-underline:hover::after {
            width: 100%;
          }
        `}</style>
      </footer>
    </section>
  );
};

export default Footer;
