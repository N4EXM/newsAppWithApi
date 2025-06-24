import React, {useContext, useState} from 'react'
import { useNavigate } from 'react-router-dom'
import UserCard from '../components/settingComponents/UserCard'
import UserArticles from '../components/settingComponents/UserArticles'
import GeneralSettings from '../components/settingComponents/GeneralSettings'
import userImage from '../assets/userImage.png'

const Settings = () => {

  const [warningBox, setWarningBox] = useState(false)

  const navigate = useNavigate()
    
  const handleNavigate = (page) => {
    navigate(`/${page}`)
  }

  const toggleWarningBox = () => {
    setWarningBox(true)
  }  

  return (
    <div className='w-full h-full p-5 flex flex-col gap-5 pb-24 z-10 relative'>

      {/* logout warning box */}
      <div className={`${warningBox ? "flex" : "hidden"} border border-text/20 dark:border-darkText/20 fixed w-4/5 h-32 z-40 bg-background flex-col rounded-sm dark:bg-darkBackground flex justify-between left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2`}>
        
        <div className='w-full h-full gap-1 flex p-4 flex-col items-center justify-center'>
          <h1 className='text-sm font-medium h-fit text-center'>Are you sure you want to Logout</h1>
        </div>

        <div className='w-full h-full flex items-end p-2 flex-row gap-2'>
          <button onClick={() => setWarningBox(false)} className='w-full p-2 bg-secBackground font-medium dark:bg-secBackground/10 h-fit border border-text/20 dark:border-darkText/20 text-sm rounded-sm'>
            Cancel
          </button>
          <button onClick={() => handleNavigate("Login")} className='bg-rose-600 border border-rose-600 w-full h-fit text-darkText font-medium rounded-sm p-2 text-sm'> 
            Exit
          </button>
        </div>

      </div>

      <div className='flex items-center gap-3'>
        <p className='text-lg font-semibold text-darkText'>Settings</p>
      </div>

      <UserCard
        editFunction={() => handleNavigate("UserEdit")}
        toggleWarningBox={() => toggleWarningBox()}
      />

      <div className='flex flex-col gap-4 pt-2 w-full h-full'>
        <UserArticles/>
        <GeneralSettings/>
      </div>

      

    </div>
  )
}

export default Settings