import axios from 'axios'
import React, { createContext, useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

export const AuthContext = createContext()

export const AuthProvider = ({children}) => {

    const [isAuthenticated, setIsAuthenticated] = useState(false) // checks if the user is actually logged in
    const [isLoading, setIsLoading] = useState(false) // checks if users data is retrieved
    const [userInfo, setUserInfo] = useState(null) // 
    const [userData, setUserData] = useState(null) // contains the user data
    const [isLoginSuccessful, setIsLoginSuccessful] = useState(false)

    const navigate = useNavigate();

    const login = async (email, password) => {
        try {
            const response = await axios.post("http://localhost:3000/server/api/users/get_user.php", {
                email,
                password,
            })

            if (response.data.success) {
                setUserInfo({
                    email: email,
                    password: password
                })
                console.log(userInfo)
                localStorage.setItem("userInfo",userInfo)
                setUserData(response.data.userData)
                setIsLoading(true)
            }
            else {
                console.log("couldn't retrieve user data")
            }

        }
        catch (error) {
            console.log(error.message)
        }
    }

    const handleNavigate = () => {
        navigate("/")
    }

    useEffect(() => {

        if (localStorage.getItem("userInfo")) {
            setUserInfo(JSON.parse(localStorage.get("userInfo")))
            login(userInfo.email,userInfo.password)
            handleNavigate()
        }

    }, [])

    useEffect(() => {
        if (userData != null && isLoading) {
            handleNavigate()
        }
    }, [isLoading])

    return (
        <AuthContext.Provider value={{ login, userData }}>
            {children}
        </AuthContext.Provider>
    );

}