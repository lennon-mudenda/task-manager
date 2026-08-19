import "../css/app.css"

import React from "react"
import { createRoot } from "react-dom/client"
import {Button} from "@/components/ui/button";

function App() {
    return (
        <main className="flex min-h-screen items-center justify-center">
            <Button>
                Hello from Laravel
            </Button>
        </main>
    )
}

const element = document.getElementById("app")

if (element) {
    createRoot(element).render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    )
}
