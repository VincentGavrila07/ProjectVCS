import React from "react";
import ReactDOM from "react-dom/client";
import Navbar from "./components/navbar";
import HeroSection from "./components/HeroSection";
import AboutUs from "./components/AboutUs";
import Footer from "./components/Footer";
import TutorGrid from "./components/TutorGrid";
import Forum from "./components/Forum";

function App() {
    return (
        <>
            <Navbar /> 
            <HeroSection/>
            <TutorGrid/>

            <AboutUs/>
            <Forum/>
            <Footer/>

        </>
    );
}

ReactDOM.createRoot(document.getElementById("root")).render(<App />);
