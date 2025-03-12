import React from "react";
import ReactDOM from "react-dom/client";
import Navbar from "./components/navbar";
import HeroSection from "./components/HeroSection";
import AboutUs from "./components/AboutUs";

function App() {
    return (
        <>
            <Navbar /> {/* Pakai huruf kapital di awal */}
            <HeroSection/>
            <AboutUs/>

        </>
    );
}

ReactDOM.createRoot(document.getElementById("root")).render(<App />);
