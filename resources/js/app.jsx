import React from "react";
import ReactDOM from "react-dom/client";
import Navbar from "./components/navbar";
import HeroSection from "./components/HeroSection";
import AboutUs from "./components/AboutUs";
import Footer from "./components/Footer";
import TutorGrid from "./components/TutorGrid";

function App() {
    return (
        <>
            <Navbar /> 
            <HeroSection/>
            <TutorGrid/>

            <AboutUs/>
            <Footer/>

        </>
    );
}

ReactDOM.createRoot(document.getElementById("root")).render(<App />);
