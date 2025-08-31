# Namecheap DNS Configuration for Amazon SES

## Step-by-Step Namecheap Setup

### 1. Access DNS Management
1. Log into your Namecheap account
2. Go to **Domain List**
3. Find your domain → Click **Manage**
4. Click **Advanced DNS** tab

### 2. Current DNS Records Analysis
Before adding SES records, document your current DNS setup:
- **A Records**: Main domain pointing to your server
- **CNAME Records**: Subdomains (www, mail, etc.)
- **MX Records**: Email routing (if any)
- **TXT Records**: SPF, existing verification records

### 3. Add Amazon SES DKIM Records

When you verify your domain in SES, you'll get 3 CNAME records. Add them exactly as provided:

#### Example DKIM Records (yours will be different):
| Type | Host | Value | TTL |
|------|------|-------|-----|
| CNAME | `abc123def456._domainkey` | `abc123def456.dkim.amazonses.com` | Automatic |
| CNAME | `xyz789uvw012._domainkey` | `xyz789uvw012.dkim.amazonses.com` | Automatic |
| CNAME | `ghi345jkl678._domainkey` | `ghi345jkl678.dkim.amazonses.com` | Automatic |

**Important Notes:**
- Remove the domain suffix from Host field (Namecheap adds it automatically)
- If SES shows `abc123def456._domainkey.yourdomain.com`, enter only `abc123def456._domainkey`
- TTL: Use "Automatic" (or 300 seconds for faster propagation during testing)

### 4. SPF Record Configuration

#### Option A: If you have NO existing SPF record
| Type | Host | Value | TTL |
|------|------|-------|-----|
| TXT | @ | `v=spf1 include:amazonses.com ~all` | Automatic |

#### Option B: If you have an EXISTING SPF record
Find your existing SPF record and modify it to include SES:

**Before:**
```
v=spf1 include:_spf.google.com ~all
```

**After:**
```
v=spf1 include:_spf.google.com include:amazonses.com ~all
```

**⚠️ WARNING**: Only have ONE SPF record per domain!

### 5. DMARC Record (Recommended)

Add a DMARC record for better email deliverability:

| Type | Host | Value | TTL |
|------|------|-------|-----|
| TXT | _dmarc | `v=DMARC1; p=quarantine; rua=mailto:dmarc-reports@yourdomain.com; ruf=mailto:dmarc-failures@yourdomain.com; fo=1` | Automatic |

**DMARC Policy Options:**
- `p=none`: Monitor only (recommended for testing)
- `p=quarantine`: Send suspicious emails to spam
- `p=reject`: Reject suspicious emails entirely

### 6. MX Records (If Using Domain for Receiving Email)

If you want to receive emails at your domain:

| Type | Host | Value | Priority | TTL |
|------|------|-------|----------|-----|
| MX | @ | `inbound-smtp.us-east-1.amazonaws.com` | 10 | Automatic |

**Note**: Only add if you plan to receive emails through SES. For send-only, skip this.

### 7. Verification Process

#### 7.1 DNS Propagation Check
After adding records, check propagation:
```bash
# Check DKIM records
dig TXT abc123def456._domainkey.yourdomain.com

# Check SPF record  
dig TXT yourdomain.com

# Check DMARC record
dig TXT _dmarc.yourdomain.com
```

#### 7.2 Online DNS Tools
Use these tools to verify DNS propagation:
- https://mxtoolbox.com/dmarc.aspx
- https://dmarcian.com/dmarc-inspector/
- https://whatsmydns.net/

### 8. Common Namecheap Issues & Solutions

#### Issue 1: CNAME Record Format
**Problem**: Namecheap rejects CNAME with full domain
**Solution**: Only enter the subdomain part
- ❌ Wrong: `abc123._domainkey.yourdomain.com`
- ✅ Correct: `abc123._domainkey`

#### Issue 2: Multiple SPF Records
**Problem**: Duplicate SPF records break email authentication
**Solution**: Combine all includes into one SPF record

#### Issue 3: TTL Settings
**Problem**: Changes take too long to propagate
**Solution**: Set TTL to 300 seconds during setup, change to 3600 after verification

### 9. Record Summary Table Template

Fill this out with your actual values:

| Record Type | Host | Value | Purpose |
|-------------|------|-------|---------|
| CNAME | `[string1]._domainkey` | `[string1].dkim.amazonses.com` | DKIM 1 |
| CNAME | `[string2]._domainkey` | `[string2].dkim.amazonses.com` | DKIM 2 |
| CNAME | `[string3]._domainkey` | `[string3].dkim.amazonses.com` | DKIM 3 |
| TXT | @ | `v=spf1 include:amazonses.com ~all` | SPF |
| TXT | _dmarc | `v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com` | DMARC |

### 10. Validation Checklist

Before going live:
- [ ] All 3 DKIM records added to Namecheap
- [ ] SPF record includes amazonses.com
- [ ] DMARC record configured
- [ ] DNS propagation completed (24-48 hours)
- [ ] SES domain shows "Verified" status
- [ ] SES account moved out of sandbox mode
- [ ] Test email sent successfully
- [ ] Email appears in inbox (not spam)

### 11. Post-Setup Monitoring

#### SES Console Metrics to Watch:
- **Bounce rate**: Keep < 5%
- **Complaint rate**: Keep < 0.1%
- **Reputation**: Maintain "High" status
- **Sending quota**: Monitor daily sending limits

#### Set Up CloudWatch Alarms:
- High bounce rate alerts
- High complaint rate alerts
- Sending quota near limit