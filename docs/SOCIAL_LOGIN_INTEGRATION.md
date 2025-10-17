# Social Login Integration Documentation

## Overview
This document explains how to integrate Google and Facebook social login with your Laravel API backend from your frontend application.

## Setup Requirements

### 1. Google OAuth Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API and Google OAuth2 API
4. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
5. Set authorized redirect URIs:
   - For development: `http://localhost:3000/auth/google/callback`
   - For production: `https://yourdomain.com/auth/google/callback`
6. Copy the Client ID and Client Secret to your `.env` file

### 2. Facebook OAuth Setup
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or use existing one
3. Add "Facebook Login" product to your app
4. In Facebook Login settings, add valid OAuth redirect URIs:
   - For development: `http://localhost:3000/auth/facebook/callback`
   - For production: `https://yourdomain.com/auth/facebook/callback`
5. Copy the App ID and App Secret to your `.env` file

### 3. Environment Variables
Update your `.env` file with actual credentials:
```env
GOOGLE_CLIENT_ID=your_actual_google_client_id
GOOGLE_CLIENT_SECRET=your_actual_google_client_secret
FACEBOOK_CLIENT_ID=your_actual_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_actual_facebook_app_secret
```

## API Endpoints

### Available Endpoints
- `GET /api/v1/auth/google/redirect` - Get Google OAuth URL
- `POST /api/v1/auth/google/callback` - Handle Google callback
- `GET /api/v1/auth/facebook/redirect` - Get Facebook OAuth URL  
- `POST /api/v1/auth/facebook/callback` - Handle Facebook callback
- `POST /api/v1/auth/complete-social-onboarding` - Complete company setup (requires auth token)

## Frontend Integration

### Method 1: Direct OAuth Flow (Recommended)

#### Step 1: Get OAuth URL
```javascript
// Get Google OAuth URL
const getGoogleAuthUrl = async () => {
  try {
    const response = await fetch('/api/v1/auth/google/redirect');
    const data = await response.json();
    return data.redirect_url;
  } catch (error) {
    console.error('Error getting Google auth URL:', error);
  }
};

// Get Facebook OAuth URL
const getFacebookAuthUrl = async () => {
  try {
    const response = await fetch('/api/v1/auth/facebook/redirect');
    const data = await response.json();
    return data.redirect_url;
  } catch (error) {
    console.error('Error getting Facebook auth URL:', error);
  }
};
```

#### Step 2: Redirect User to OAuth Provider
```javascript
// Google login button handler
const handleGoogleLogin = async () => {
  const authUrl = await getGoogleAuthUrl();
  if (authUrl) {
    window.location.href = authUrl;
  }
};

// Facebook login button handler
const handleFacebookLogin = async () => {
  const authUrl = await getFacebookAuthUrl();
  if (authUrl) {
    window.location.href = authUrl;
  }
};
```

#### Step 3: Handle OAuth Callback
Create callback pages in your frontend router:

```javascript
// pages/auth/google/callback.js
import { useEffect } from 'react';
import { useRouter } from 'next/router';

export default function GoogleCallback() {
  const router = useRouter();
  
  useEffect(() => {
    const handleCallback = async () => {
      const { code, state } = router.query;
      
      if (code) {
        try {
          const response = await fetch('/api/v1/auth/google/callback', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ code, state }),
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Store auth token
            localStorage.setItem('auth_token', data.data.token);
            
            // Check if user needs to complete company setup
            if (data.data.needs_company_setup) {
              router.push('/onboarding/company-setup');
            } else {
              router.push('/dashboard');
            }
          } else {
            // Handle error
            console.error('Google login failed:', data.message);
            router.push('/login?error=google_auth_failed');
          }
        } catch (error) {
          console.error('Error during Google callback:', error);
          router.push('/login?error=callback_failed');
        }
      }
    };
    
    if (router.isReady) {
      handleCallback();
    }
  }, [router.isReady, router.query]);
  
  return <div>Processing Google login...</div>;
}
```

```javascript
// pages/auth/facebook/callback.js
import { useEffect } from 'react';
import { useRouter } from 'next/router';

export default function FacebookCallback() {
  const router = useRouter();
  
  useEffect(() => {
    const handleCallback = async () => {
      const { code, state } = router.query;
      
      if (code) {
        try {
          const response = await fetch('/api/v1/auth/facebook/callback', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ code, state }),
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Store auth token
            localStorage.setItem('auth_token', data.data.token);
            
            // Check if user needs to complete company setup
            if (data.data.needs_company_setup) {
              router.push('/onboarding/company-setup');
            } else {
              router.push('/dashboard');
            }
          } else {
            // Handle error
            console.error('Facebook login failed:', data.message);
            router.push('/login?error=facebook_auth_failed');
          }
        } catch (error) {
          console.error('Error during Facebook callback:', error);
          router.push('/login?error=callback_failed');
        }
      }
    };
    
    if (router.isReady) {
      handleCallback();
    }
  }, [router.isReady, router.query]);
  
  return <div>Processing Facebook login...</div>;
}
```

#### Step 4: Complete Company Onboarding (if needed)
```javascript
// pages/onboarding/company-setup.js
import { useState } from 'react';
import { useRouter } from 'next/router';

export default function CompanySetup() {
  const [formData, setFormData] = useState({
    company_name: '',
    company_type: 'construction',
    phone: '',
    address: ''
  });
  const [loading, setLoading] = useState(false);
  const router = useRouter();
  
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/auth/complete-social-onboarding', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(formData),
      });
      
      const data = await response.json();
      
      if (data.success) {
        router.push('/dashboard');
      } else {
        console.error('Company setup failed:', data.message);
      }
    } catch (error) {
      console.error('Error completing company setup:', error);
    } finally {
      setLoading(false);
    }
  };
  
  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        placeholder="Company Name"
        value={formData.company_name}
        onChange={(e) => setFormData({...formData, company_name: e.target.value})}
        required
      />
      
      <select
        value={formData.company_type}
        onChange={(e) => setFormData({...formData, company_type: e.target.value})}
        required
      >
        <option value="construction">Construction</option>
        <option value="renovation">Renovation</option>
        <option value="consulting">Consulting</option>
        <option value="other">Other</option>
      </select>
      
      <input
        type="tel"
        placeholder="Phone (optional)"
        value={formData.phone}
        onChange={(e) => setFormData({...formData, phone: e.target.value})}
      />
      
      <textarea
        placeholder="Address (optional)"
        value={formData.address}
        onChange={(e) => setFormData({...formData, address: e.target.value})}
      />
      
      <button type="submit" disabled={loading}>
        {loading ? 'Setting up...' : 'Complete Setup'}
      </button>
    </form>
  );
}
```

### Method 2: Popup Window Flow

```javascript
// Alternative popup-based approach
const handleSocialLoginPopup = async (provider) => {
  const authUrl = provider === 'google' 
    ? await getGoogleAuthUrl() 
    : await getFacebookAuthUrl();
    
  const popup = window.open(
    authUrl,
    'social-login',
    'width=500,height=600,scrollbars=yes,resizable=yes'
  );
  
  // Listen for popup messages
  const handleMessage = (event) => {
    if (event.origin !== window.location.origin) return;
    
    if (event.data.type === 'SOCIAL_LOGIN_SUCCESS') {
      popup.close();
      // Handle successful login
      localStorage.setItem('auth_token', event.data.token);
      
      if (event.data.needs_company_setup) {
        router.push('/onboarding/company-setup');
      } else {
        router.push('/dashboard');
      }
    }
  };
  
  window.addEventListener('message', handleMessage);
  
  // Clean up listener when popup closes
  const checkClosed = setInterval(() => {
    if (popup.closed) {
      clearInterval(checkClosed);
      window.removeEventListener('message', handleMessage);
    }
  }, 1000);
};
```

## React Components Example

### Login Button Component
```jsx
// components/SocialLoginButtons.jsx
import React from 'react';

export default function SocialLoginButtons() {
  const handleGoogleLogin = async () => {
    const response = await fetch('/api/v1/auth/google/redirect');
    const data = await response.json();
    if (data.redirect_url) {
      window.location.href = data.redirect_url;
    }
  };
  
  const handleFacebookLogin = async () => {
    const response = await fetch('/api/v1/auth/facebook/redirect');
    const data = await response.json();
    if (data.redirect_url) {
      window.location.href = data.redirect_url;
    }
  };
  
  return (
    <div className="social-login-buttons">
      <button 
        onClick={handleGoogleLogin}
        className="google-login-btn"
      >
        <svg>{ /* Google icon SVG */ }</svg>
        Continue with Google
      </button>
      
      <button 
        onClick={handleFacebookLogin}
        className="facebook-login-btn"
      >
        <svg>{ /* Facebook icon SVG */ }</svg>
        Continue with Facebook
      </button>
    </div>
  );
}
```

## API Response Formats

### Successful Authentication Response
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "first_name": "John",
      "last_name": "Doe",
      "avatar": "https://...",
      "social_provider": "google"
    },
    "token": "1|abcd1234...",
    "needs_company_setup": true,
    "social_data": {
      "company_name_suggestions": [
        "John Doe Construction",
        "Doe & Associates",
        "John Doe Contractors"
      ]
    }
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Google authentication failed",
  "error": "Invalid authorization code"
}
```

### Complete Onboarding Response
```json
{
  "success": true,
  "data": {
    "company": {
      "id": 1,
      "name": "John Doe Construction",
      "type": "construction"
    },
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "onboarding_completed": true
  }
}
```

## Error Handling

```javascript
const handleSocialLoginError = (error, provider) => {
  console.error(`${provider} login error:`, error);
  
  // Show user-friendly error messages
  const errorMessages = {
    'access_denied': 'You cancelled the login process',
    'invalid_request': 'Something went wrong. Please try again.',
    'server_error': 'Server error. Please try again later.'
  };
  
  const message = errorMessages[error] || 'Login failed. Please try again.';
  // Show error to user (toast, alert, etc.)
};
```

## Testing

### Test URLs
- Development: `http://localhost:3000/auth/google/callback`
- Production: `https://yourdomain.com/auth/google/callback`

### Test Flow
1. Click social login button
2. Redirect to OAuth provider
3. Authorize application
4. Redirect back to callback URL
5. Extract authorization code
6. Send code to your API
7. Receive user data and token
8. Complete onboarding if needed

## Security Considerations

1. **HTTPS Required**: OAuth providers require HTTPS in production
2. **State Parameter**: Used to prevent CSRF attacks (handled automatically)
3. **Token Storage**: Store auth tokens securely (consider httpOnly cookies for sensitive apps)
4. **Redirect URI Validation**: Must match exactly what's configured in OAuth providers
5. **CORS**: Ensure your API allows requests from your frontend domain

## Troubleshooting

### Common Issues
1. **Redirect URI Mismatch**: Ensure callback URLs match in OAuth provider settings
2. **CORS Errors**: Configure CORS properly in Laravel
3. **Invalid Client**: Check client ID/secret in `.env` file
4. **Scope Issues**: Ensure proper scopes are requested (email, profile)

This documentation provides everything needed to integrate social login in your frontend application with the Laravel API backend we created.