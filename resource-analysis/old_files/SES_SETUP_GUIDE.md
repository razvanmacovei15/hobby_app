# Amazon SES + Namecheap Domain Setup Guide

## Overview
This guide walks you through setting up Amazon SES for production email delivery with your Namecheap domain.

## Current vs Production Configuration

### Current Setup (Local Development)
- **Mailer**: SMTP via Docker Mailpit container
- **Host**: mailpit:1025
- **Testing**: All emails caught in Mailpit dashboard at localhost:8125

### Production Setup (Amazon SES)
- **Mailer**: Amazon SES
- **Delivery**: Real email delivery via AWS infrastructure
- **Domain**: Your verified Namecheap domain

## Step 1: Amazon SES Configuration

### 1.1 Create AWS Account & Access SES
1. Log into AWS Console
2. Navigate to Amazon SES (Simple Email Service)
3. Choose region: **us-east-1** (Virginia) - recommended for SES

### 1.2 Verify Your Domain
1. In SES Console → **Verified identities** → **Create identity**
2. Choose **Domain**
3. Enter your domain: `yourdomain.com`
4. **Enable DKIM signing**: ✅ Yes
5. **DKIM signing key length**: 2048-bit (recommended)
6. Click **Create identity**

### 1.3 Get DKIM Records
After domain creation, SES will provide 3 CNAME records:
```
Name: [random-string-1]._domainkey.yourdomain.com
Value: [random-string-1].dkim.amazonses.com

Name: [random-string-2]._domainkey.yourdomain.com  
Value: [random-string-2].dkim.amazonses.com

Name: [random-string-3]._domainkey.yourdomain.com
Value: [random-string-3].dkim.amazonses.com
```

### 1.4 Create IAM User for SES
1. Go to **IAM** → **Users** → **Create user**
2. Username: `construction-tool-ses-user`
3. **Attach policies directly** → Search and select:
   - `AmazonSESFullAccess` (or create custom policy for least privilege)
4. Create user
5. Go to user → **Security credentials** → **Create access key**
6. Choose **Application running outside AWS**
7. Save the **Access Key ID** and **Secret Access Key**

## Step 2: Namecheap DNS Configuration

### 2.1 Access Namecheap DNS
1. Log into Namecheap account
2. Go to **Domain List** → Find your domain → **Manage**
3. Go to **Advanced DNS** tab

### 2.2 Add DKIM Records
Add the 3 CNAME records from SES (Step 1.3):

| Type | Host | Value | TTL |
|------|------|-------|-----|
| CNAME | [random-string-1]._domainkey | [random-string-1].dkim.amazonses.com | Automatic |
| CNAME | [random-string-2]._domainkey | [random-string-2].dkim.amazonses.com | Automatic |
| CNAME | [random-string-3]._domainkey | [random-string-3].dkim.amazonses.com | Automatic |

### 2.3 Add SPF Record (if not exists)
| Type | Host | Value | TTL |
|------|------|-------|-----|
| TXT | @ | `v=spf1 include:amazonses.com ~all` | Automatic |

### 2.4 Add DMARC Record (recommended)
| Type | Host | Value | TTL |
|------|------|-------|-----|
| TXT | _dmarc | `v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com` | Automatic |

## Step 3: Environment Configuration

### 3.1 Update .env.production
Replace these values in your `.env.production` file:

```env
# Change these values:
APP_URL=https://yourdomain.com
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# Add your AWS credentials:
AWS_ACCESS_KEY_ID=your-actual-access-key-id
AWS_SECRET_ACCESS_KEY=your-actual-secret-access-key
```

### 3.2 Deploy Environment File
Copy `.env.production` to your production server as `.env`

## Step 4: Verification & Testing

### 4.1 Verify Domain in SES
1. Wait 24-72 hours for DNS propagation
2. In SES Console → **Verified identities** → Your domain
3. Status should show **Verified** with green checkmark
4. DKIM status should show **Successful**

### 4.2 Request Production Access
**IMPORTANT**: New SES accounts start in sandbox mode (can only send to verified emails)

1. In SES Console → **Account dashboard** → **Request production access**
2. Fill out the form:
   - **Mail type**: Transactional
   - **Website URL**: https://yourdomain.com
   - **Use case**: User registration, password resets, workspace invitations
   - **Expected sending volume**: Start with 200 emails/day
   - **Bounce/complaint handling**: Explain your process

### 4.3 Test Email Sending
1. Deploy your application with new environment
2. Test user invitation flow
3. Check email delivery and spam folder
4. Monitor SES metrics in AWS Console

## Step 5: Email Templates & Compliance

### 5.1 Update Email Templates
Your current email templates in `app/Mail/` should work as-is, but ensure:
- Proper unsubscribe links (for marketing emails)
- Clear sender identification
- Professional formatting

### 5.2 Monitor Bounce & Complaint Rates
- Keep bounce rate < 5%
- Keep complaint rate < 0.1%
- Set up SNS notifications for bounces/complaints

## Current Mail Configuration Analysis

### Your Current Email System
- **Invitation emails**: `WorkspaceInvitationMail`
- **From address**: `noreply@macocoding.com` (needs to match verified domain)
- **Email logging**: `EmailLog` model tracks delivery status
- **Templates**: Using Laravel Mailable classes

### Required Changes for Production
1. **Domain alignment**: Change `MAIL_FROM_ADDRESS` to match your verified domain
2. **Environment**: Switch from `smtp`/`mailpit` to `ses`
3. **Monitoring**: Consider adding bounce/complaint handling
4. **Rate limiting**: SES has sending quotas (start: 200/day, 1/second)

## Security Notes
- Store AWS credentials securely (consider AWS IAM roles for EC2 if hosting on AWS)
- Use least-privilege IAM policies
- Monitor AWS CloudTrail for SES API usage
- Regularly rotate access keys

## Cost Estimation
- **SES Pricing**: $0.10 per 1,000 emails
- **Free tier**: 62,000 emails/month (if sending from EC2)
- **Your usage**: Invitation emails, notifications → Very low cost

## Troubleshooting Common Issues
1. **Domain not verifying**: Check DNS propagation with `dig` or online tools
2. **Emails not sending**: Check SES sending statistics for bounces/complaints
3. **Spam folder delivery**: Improve SPF/DKIM/DMARC records
4. **Rate limiting**: Request quota increase in SES console