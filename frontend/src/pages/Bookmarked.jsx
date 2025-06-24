import React, { useContext } from 'react'
import articles from '../articles'
import NewsCard from '../components/generalComponents/NewsCard'
import { AuthContext } from '../context/AuthContext'

const Bookmarked = () => {

  const { isAuthenticated } = useContext(AuthContext)

  return (
    <div className='w-full h-full p-5 flex flex-col gap-5 pb-24 z-10 min-h-screen relative bg-background dark:bg-darkBackground'>
      
      <div className='w-full flex flex-row items-center gap-3'>
        <p className='text-lg font-semibold'>Read Later</p>
      </div>

      <div className='w-full h-full flex flex-col gap-2'>
        {isAuthenticated ? 
        
         articles.map((article, _) => (
          <NewsCard
            key={article.id}
            id={article.id}
            articleImage={article.articleImage}
            authorImage={article.authorImage}
            title={article.title}
            authorName={article.authorName}
            date={article.date}
            category={article.category[0]}
            content={article.content}
          />
        ))

        :
        
          <div className='flex items-center justify-center min-h-[75vh] w-full h-full'>
            <h1 className='font-semibold text-text/60 dark:text-darkText/60'>Sign in to see Saved articles</h1>
          </div>

        }
      </div>

    </div>
  )
}

export default Bookmarked